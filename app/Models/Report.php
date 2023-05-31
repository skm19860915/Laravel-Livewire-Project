<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\{User};

class Report extends Model
{
  use HasFactory;

  public static function salesByProduct($start, $end)
  {
    $current_location = session('current_location');
    $locationId =  $current_location->id;

    $start = strip_tags(DB::connection()->getPdo()->quote($start));
    $end = strip_tags(DB::connection()->getPdo()->quote($end));

    $sql = "
      SELECT 
          DISTINCT
          p.id,
          p.name, 
          s.new_customer,
          COALESCE(NULLIF(t.refill, 0), s.reorder, 0) as reorder,
          COUNT(t.id) as orders, 
          FORMAT(AVG(tp.custom_amount), 0) as avg_duration, 
          ROUND(AVG(tp.custom_price), 2) as avg_sale, 
          ROUND(SUM(tp.custom_price), 2) as total_sales,
          IF(t.balanc_during_visit > 0, t.amount_paid_during_office_visit/p2.product_count, SUM(tp.custom_price)) AS total_paid
      FROM ticket_products tp
      JOIN products p ON tp.product_id = p.id
      JOIN tickets t ON tp.ticket_id = t.id
      JOIN schedules s ON t.schedule_id = s.id
      JOIN (
          SELECT 
            tp2.ticket_id,
            COUNT(tp2.id) AS product_count
          FROM ticket_products tp2
          JOIN products p2 ON tp2.product_id = p2.id
            WHERE p2.location_id  = {$locationId}
            GROUP BY tp2.ticket_id
      ) p2 ON t.id = p2.ticket_id
      WHERE t.date BETWEEN {$start} AND {$end}
            AND t.revisit = 0
            AND t.location_id = {$locationId}
            AND t.total > 0
            GROUP BY p.name, s.new_customer, COALESCE(s.reorder, 0)
            ORDER BY p.name ASC";


    $detail = collect(DB::select($sql));


    $sql = "
      SELECT 
          p.id,
          p.name, 
          COUNT(t.id) as orders, 
          FORMAT(AVG(tp.custom_amount), 0) as avg_duration,
          ROUND(AVG(tp.custom_price), 2) as avg_sale,
          ROUND(SUM(tp.custom_price), 2) as total_sales,
          IF(t.balanc_during_visit > 0, t.amount_paid_during_office_visit/p2.product_count, SUM(tp.custom_price)) AS total_paid
      FROM ticket_products tp
      JOIN products p ON tp.product_id = p.id
      JOIN tickets t ON tp.ticket_id = t.id
      JOIN schedules s ON t.schedule_id = s.id
      JOIN (
          SELECT 
            tp2.ticket_id,
            COUNT(tp2.id) AS product_count
          FROM ticket_products tp2
          JOIN products p2 ON tp2.product_id = p2.id
            WHERE p2.location_id  = {$locationId}
            GROUP BY tp2.ticket_id
      ) p2 ON t.id = p2.ticket_id
      WHERE t.date BETWEEN {$start} AND {$end}
        AND t.revisit = 0
        AND t.location_id = {$locationId}
        AND t.total > 0
        GROUP BY p.id, p.name
        ORDER BY COUNT(t.id) DESC";


    $productTotals = collect(DB::select($sql));


    $sql = "
      SELECT 
          t.location_id,
          COUNT(t.id) as orders, 
          s.new_customer,
          COALESCE(NULLIF(t.refill, 0), s.reorder, 0) as reorder,
          FORMAT(AVG(tp.custom_amount), 0) as avg_duration,
          ROUND(AVG(tp.custom_price), 2) as avg_sale,
          ROUND(SUM(tp.custom_price), 2) as total_sales,
          IF(t.balanc_during_visit > 0, t.amount_paid_during_office_visit/p2.product_count, SUM(tp.custom_price)) AS total_paid
      FROM ticket_products tp
      JOIN products p ON tp.product_id = p.id
      JOIN tickets t ON tp.ticket_id = t.id
      JOIN schedules s ON t.schedule_id = s.id
      JOIN (
          SELECT 
            tp2.ticket_id,
            COUNT(tp2.id) AS product_count
          FROM ticket_products tp2
        JOIN products p2 ON tp2.product_id = p2.id
          WHERE p2.location_id  = {$locationId}
          GROUP BY tp2.ticket_id
      ) p2 ON t.id = p2.ticket_id
      WHERE t.date BETWEEN {$start} AND {$end}
            AND t.revisit = 0
            AND t.location_id = {$locationId}
            AND t.total > 0
            GROUP BY t.location_id, s.new_customer, COALESCE(s.reorder, 0)";


    $totalDetails = collect(DB::select($sql));


    $sql = "
      SELECT 
          t.location_id,
          COUNT(t.id) as orders, 
          FORMAT(AVG(tp.custom_amount), 0) as avg_duration,
          ROUND(AVG(tp.custom_price), 2) as avg_sale,
          ROUND(SUM(tp.custom_price), 2) as total_sales,
          IF(t.balanc_during_visit > 0, t.amount_paid_during_office_visit/p2.product_count, SUM(tp.custom_price)) AS total_paid
      FROM ticket_products tp
      JOIN products p ON tp.product_id = p.id
      JOIN tickets t ON tp.ticket_id = t.id
      JOIN schedules s ON t.schedule_id = s.id
      JOIN (
          SELECT 
            tp2.ticket_id,
            COUNT(tp2.id) AS product_count
          FROM ticket_products tp2
          JOIN products p2 ON tp2.product_id = p2.id
            WHERE p2.location_id  = {$locationId}
            GROUP BY tp2.ticket_id
      ) p2 ON t.id = p2.ticket_id
      WHERE t.date BETWEEN {$start} AND {$end}
        AND t.revisit = 0
        AND t.location_id = {$locationId}
        AND t.total > 0
      GROUP BY t.location_id";


    $totals = collect(DB::select($sql));

    return array('detail' => $detail, 'product_totals' => $productTotals, 'totals' => $totals, 'total_details' => $totalDetails);
  }

  public static function marketingReport($start, $end)
  {
    $current_location = session('current_location');
    $locationId =  $current_location->id;

    $start = strip_tags(DB::connection()->getPdo()->quote($start));
    $end = strip_tags(DB::connection()->getPdo()->quote($end));

    $sqlString = 'SELECT ms.id, ms.description,
        COALESCE(x1.TotalPaid, 0) as paid_amount,
        COALESCE(x2.AveragePaid, 0) as avg_paid_amount,
        COALESCE(x2.AverageTotal, 0) as avg_total_amount,
        COALESCE(x.AverageAge, 0) as avg_age,
        COALESCE(x.Booked, 0) as booked,
        COALESCE(x1.Rescheduled, 0) as reschedule,
        COALESCE(x1.Cancelled, 0) as cancel,
        COALESCE(x1.Confirmed, 0 ) as confirm,
        COALESCE(x1.Showed, 0) as shows,
        COALESCE(x3.TrimixTickets, 0) as trimix,
        COALESCE(x3.TrimixDoses, 0) as doses_trimix,
        COALESCE(x3.SubTickets, 0) as sublingual,
        COALESCE(x3.SubDoses, 0) as doses_sublingual,
        COALESCE(x3.TestosteroneTickets, 0)  as testosterones,
        COALESCE(x3.TestosteroneDoses, 0) as doses_testosterones
        FROM marketing_sources ms
        JOIN marketing_locations ml
          ON ms.id = ml.marketing_source_id
        LEFT JOIN patients p
          ON ms.id = p.how_did_hear_about_clinic
        LEFT JOIN (
          SELECT
            ms1.id, SUM(IF(s.new_customer = 1, 1, 0)) as Booked,
            ROUND(AVG(TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()))) AS AverageAge
          FROM patients p
          JOIN marketing_sources ms1
          ON ms1.id = p.how_did_hear_about_clinic
          JOIN marketing_locations ml
          ON ms1.id = ml.marketing_source_id
          LEFT JOIN schedules s
          ON p.id = s.patient_id
          AND DATE(s.created_at) BETWEEN ' . $start . ' AND ' . $end . '
          AND s.location_id = ' . $locationId . '
          WHERE ml.location_id = ' . $locationId . '
          GROUP BY ms1.id
        )  x ON ms.id = x.id
        LEFT JOIN (
          SELECT ms1.id,
            SUM(IF(s.schedule_type_id = 5, 1, 0)) as Rescheduled,
            SUM(IF(s.schedule_type_id = 4, 1, 0)) as Cancelled,
            SUM(IF(s.schedule_type_id = 3, 1, 0)) as Confirmed,
            SUM(IF(s.schedule_type_id = 3 AND t.id IS NOT NULL, 1, 0)) as Showed,
            SUM(t.amount_paid_during_office_visit) as TotalPaid
          FROM patients p
          JOIN marketing_sources ms1
          ON ms1.id = p.how_did_hear_about_clinic
          JOIN marketing_locations ml
          ON ms1.id = ml.marketing_source_id
          LEFT JOIN schedules s
          ON p.id = s.patient_id
          AND s.date BETWEEN ' . $start . ' AND ' . $end . '
          AND s.location_id = ' . $locationId . '
          LEFT JOIN tickets t
          ON p.id = t.patient_id
          AND t.date  BETWEEN ' . $start . ' AND ' . $end . '
          AND t.location_id = ' . $locationId . '
          WHERE  ml.location_id = ' . $locationId . '
          GROUP BY ms1.id
        )  x1 ON ms.id = x1.id
        LEFT JOIN (
            SELECT
              ms1.id,
              ROUND(AVG(t.amount_paid_during_office_visit), 2) as AveragePaid,
              ROUND(AVG(t.total), 2)  as AverageTotal
            FROM patients p
            JOIN marketing_sources ms1
            ON ms1.id = p.how_did_hear_about_clinic
            JOIN marketing_locations ml
            ON ms1.id = ml.marketing_source_id
            LEFT JOIN tickets t
            ON p.id = t.patient_id
            AND t.date BETWEEN ' . $start . ' AND ' . $end . '
            AND t.location_id = ' . $locationId . '
            AND t.revisit = 0
            WHERE ml.location_id = ' . $locationId . '
              GROUP BY ms1.id
            ) x2 ON ms.id = x2.id
        LEFT JOIN (
          SELECT
              ms1.id,
                    SUM(IF(pr.name = "Trimix", 1, 0)) as TrimixTickets,
                    REPLACE(FORMAT(SUM(IF(pr.name = "Trimix", tp.custom_amount, 0)), 0), \',\', \'\') as TrimixDoses,
              SUM(IF(pr.name = "Sublinguals", 1, 0)) as SubTickets,
                    REPLACE(FORMAT(SUM(IF(pr.name = "Sublinguals", tp.custom_amount, 0)), 0), \',\', \'\')  as SubDoses,
              SUM(IF(pr.name = "Testosterone", 1, 0)) as TestosteroneTickets,
                    REPLACE(FORMAT(SUM(IF(pr.name = "Testosterone", tp.custom_amount, 0)), 0), \',\', \'\')  as TestosteroneDoses
                FROM patients p
             JOIN marketing_sources ms1
                ON ms1.id = p.how_did_hear_about_clinic
            JOIN marketing_locations ml
              ON ms1.id = ml.marketing_source_id
             LEFT JOIN tickets t
                ON p.id = t.patient_id
                 AND t.date  BETWEEN ' . $start . ' AND ' . $end . '
                 AND t.location_id = ' . $locationId . '
                 AND t.revisit = 0
            LEFT JOIN ticket_products tp
              ON t.id = tp.ticket_id
            JOIN products pr
              ON tp.product_id = pr.id
              AND t.location_id = pr.location_id
          WHERE ml.location_id = ' . $locationId . '
            GROUP BY ms1.id
           ) x3 ON ms.id = x3.id
           WHERE ml.location_id = ' . $locationId . '
          AND ms.disable = 0
          GROUP BY ms.id, ms.description
          ORDER BY ms.description ASC;';


    $results = collect(DB::select($sqlString));

    return $results;
  }

  public static function marketingTrendReport($thisPeriodStart, $priorPeriodStart)
  {
    $current_location = session('current_location');
    $locationId =  $current_location->id;

    $thisPeriodStart = strip_tags(DB::connection()->getPdo()->quote($thisPeriodStart));
    $priorPeriodStart = strip_tags(DB::connection()->getPdo()->quote($priorPeriodStart));

    $sql = '
      SELECT * FROM (
        SELECT 
            m.id,
              m.description,
              COALESCE(w1.booked, 0) as thisPeriod,
              COALESCE(w2.booked, 0) as priorPeriod,
              IF(COALESCE(w1.booked,0) >= COALESCE(w2.booked,0), 
                  COALESCE(ROUND((COALESCE(w1.booked,0)/COALESCE(w2.booked,1)) * 100, 2), 0),
                  ROUND((COALESCE(w2.booked, 0) - COALESCE(w1.booked, 0)) / COALESCE(w2.booked, 1) * -100, 2)
              ) as trend
          FROM marketing_sources m
          JOIN marketing_locations ml2 ON m.id = ml2.marketing_source_id
          LEFT JOIN (        
                  SELECT
                      ms.id, 
                      ms.description,
                      SUM(IF(s.new_customer = 1, 1, 0)) as booked
                    FROM patients p
                    JOIN marketing_sources ms ON ms.id = p.how_did_hear_about_clinic
                    JOIN marketing_locations ml ON ms.id = ml.marketing_source_id
                    JOIN schedules s ON p.id = s.patient_id
                  AND DATE(s.created_at) > ' . $thisPeriodStart . '
                  AND s.location_id = ' . $locationId . '
                    WHERE ml.location_id = ' . $locationId . '
                  AND ms.disable = 0
                GROUP BY ms.id
          ) w1 ON m.id = w1.id
          LEFT JOIN (        
                  SELECT
                      ms.id, 
                      ms.description,
                      SUM(IF(s.new_customer = 1, 1, 0)) as booked
                    FROM patients p
                    JOIN marketing_sources ms ON ms.id = p.how_did_hear_about_clinic
                    JOIN marketing_locations ml ON ms.id = ml.marketing_source_id
                    JOIN schedules s ON p.id = s.patient_id
                  AND DATE(s.created_at) BETWEEN ' . $priorPeriodStart . ' AND ' . $thisPeriodStart . '
                  AND s.location_id = ' . $locationId . '
                    WHERE ml.location_id = ' . $locationId . '
                  AND ms.disable = 0
                    GROUP BY ms.id
          ) w2 ON m.id = w2.id
          WHERE ml2.location_id = ' . $locationId . '
          AND m.disable = 0
      ) x ORDER BY trend DESC
    ';

    $results = collect(DB::select($sql));

    return $results;
  }


  public static function counselorReport($start, $end)
  {
    $current_location = session('current_location');
    $locationId = $current_location->id;

    $start = strip_tags(DB::connection()->getPdo()->quote($start));
    $end = strip_tags(DB::connection()->getPdo()->quote($end));

    $counselors  = DB::select('
          SELECT
            u.id,
            CONCAT(u.last_name,", ",u.first_name) as counselor_name,
            ROUND((COALESCE(x.AceTickets, 0) / COALESCE(x1.TotalTickets, 1)) * 100, 2) as ace,
            x2.TicketCount as number_of_tickets,
            COALESCE(x2.TotalPaid, 0) as total_down_payments,
            COALESCE(x.AverageDownPayments, 0) as avg_down_payments,
            COALESCE(x.AverageTicketAmount, 0) as avg_ticket_amount,
            x2.GrossSales as gross_sales,
            COALESCE(x3.Collections, 0) as collected_from_balances
          FROM users u
          LEFT JOIN (
            SELECT u.id,
              SUM(IF(tp.ticket_id IS NULL AND s.new_customer = 1, 1, 0)) as AceTickets,
              ROUND(AVG(t.amount_paid_during_office_visit),2) as AverageDownPayments,
              ROUND(AVG(t.total), 2) as AverageTicketAmount
            FROM tickets t
            JOIN users u ON t.user_id = u.id
            JOIN schedules s ON t.schedule_id = s.id
            LEFT JOIN  (
              SELECT ticket_id, SUM(custom_amount) as TicketProductSales
              FROM ticket_products
              GROUP BY ticket_id
            ) tp ON t.id = tp.ticket_Id
            WHERE t.location_id = ' . $locationId . '
            AND t.`date` BETWEEN ' . $start . ' AND ' . $end . '
            AND t.revisit = 0
            AND t.refill = 0
            GROUP BY u.id
          ) x ON u.ID = x.id
          LEFT JOIN (
            SELECT u.id,
              SUM(IF(s.new_customer = 1, 1,  0))  as TotalTickets
              FROM tickets t
              JOIN users u ON t.user_id = u.id
            LEFT JOIN schedules s ON t.schedule_id = s.id
              WHERE  t.`date` BETWEEN ' . $start . ' AND ' . $end . '
              AND t.revisit = 0
              AND t.refill = 0
            GROUP BY u.id
          ) x1 ON u.id = x1.id
          LEFT JOIN (
            SELECT u.id,
                COUNT(*) as TicketCount,
                  SUM(t.amount_paid_during_office_visit) as TotalPaid,
                SUM(t.total) as GrossSales
              FROM tickets t
              JOIN users u ON t.user_id = u.id
              WHERE  t.location_id = ' . $locationId . '
              AND t.`date` BETWEEN ' . $start . ' AND ' . $end . '
            GROUP BY u.id
          ) x2 ON u.id = x2.id
          LEFT JOIN (
              SELECT
                  t2.user_id, SUM(amount) as Collections
              FROM payments p
              JOIN tickets t2 ON p.ticket_id = t2.id
              WHERE refund = 0
              AND p.`date` BETWEEN ' . $start . ' AND ' . $end . '
              AND t2.location_id = ' . $locationId . '
              GROUP BY t2.user_id
          ) x3 ON u.id = x3.user_id
          GROUP BY u.id
          HAVING (number_of_tickets > 0 OR collected_from_balances > 0)
          ORDER BY u.last_name ASC;
        ');


    $totals  = DB::select('
            SELECT
                SUM(t.total) as gross_sales,
                x.AverageTicketAmount as avg_ticket_amount,
                SUM(t.amount_paid_during_office_visit) as total_down_payments,
                x.AverageDownPayments as avg_down_payments,
                COUNT(*) as number_of_tickets,
                x1.Collections as collected_from_balances
            FROM tickets t
            LEFT JOIN (
                SELECT
                    location_id,
                    AVG(total) as AverageTicketAmount,
                    AVG(amount_paid_during_office_visit) as AverageDownPayments
                FROM tickets
                WHERE location_id = ' . $locationId . '
                AND `date` BETWEEN ' . $start . ' AND ' . $end . '
                AND revisit = 0
                GROUP BY location_id
            ) x ON t.location_id = x.location_id
            LEFT JOIN (
                SELECT
                    t2.location_id, SUM(amount) as Collections
                FROM payments p
                JOIN tickets t2 ON p.ticket_id = t2.id
                WHERE refund = 0
                AND p.`date` BETWEEN ' . $start . ' AND ' . $end . '
                AND t2.location_id = ' . $locationId . '
                GROUP BY t2.location_id
            ) x1 ON t.location_id = x1.location_id
            WHERE t.location_id = ' . $locationId . '
            AND t.`date` BETWEEN ' . $start . ' AND ' . $end . '
            GROUP BY t.location_id;
        ');

    $res['counselors'] = collect($counselors);
    if (isset($totals[0])) {
      $res['totals'] = collect($totals[0])->toArray();
    } else {
      $totals['gross_sales'] = 0;
      $totals['avg_ticket_amount'] = 0;
      $totals['total_down_payments'] = 0;
      $totals['avg_down_payments'] = 0;
      $totals['number_of_tickets'] = 0;
      $totals['collected_from_balances'] = 0;
      $res['totals'] = $totals;
    }

    return  $res;
  }


  public static function dailyStats($start, $end, $nowSql, $email)
  {
    //Get user locations
    $user = User::where('email', trim($email))->first();
    $userLocations = $user->locations->pluck('id')->toArray();
    $qryLocations = join(',', $userLocations);
    //dd($qryLocations);

    $start = strip_tags(DB::connection()->getPdo()->quote($start));
    $end = strip_tags(DB::connection()->getPdo()->quote($end));

    if ($qryLocations) {
      $monthlyStats = DB::select('
                SELECT
                l.id,
                l.location_name,
                COALESCE(md.monthlyPatientsCount,0) AS monthly_patient_count,
                COALESCE(md.monthlySales,0) AS monthly_sales,
                COALESCE(md.monthlyAvgTickets,0) AS monthly_avg_tickets,
                COALESCE(md.monthlyDown,0) AS monthly_down_payments,
                COALESCE(md.monthlyAvgDown,0) AS monthly_avg_payments,
                COALESCE(md1.monthlyCollected,0) AS monthly_collected_from_balances
                FROM locations l
                LEFT JOIN (
                SELECT
                    COUNT(t.patient_id) as monthlyPatientsCount,
                    SUM(t.total) as monthlySales,
                    AVG(t.total) as monthlyAvgTickets,
                    SUM(t.amount_paid_during_office_visit) as monthlyDown,
                    AVG(t.amount_paid_during_office_visit) as monthlyAvgDown,
                    t.location_id
                FROM tickets t
                WHERE t.date BETWEEN ' . $start . ' AND ' . $end . '
                AND t.revisit = 0
                GROUP BY t.location_id
                ) md ON l.id = md.location_id
                LEFT JOIN (
                SELECT
                    SUM(pm.amount) as monthlyCollected,
                    t.location_id
                FROM tickets t
                JOIN payments pm
                ON pm.ticket_id = t.id
                AND pm.date BETWEEN ' . $start . ' AND ' . $end . '
                AND t.revisit = 0
                AND pm.refund=0
                GROUP BY t.location_id
                ) md1 ON l.id = md1.location_id
                WHERE l.id IN (' . $qryLocations . ')
                GROUP BY l.id, l.location_name
                ORDER BY monthly_sales DESC

            ');

      $dailyStats = DB::select('
                SELECT
                l.id,
                l.location_name,
                COALESCE(dd.dailyPatientsCount,0) AS daily_patient_count,
                COALESCE(dd.dailySales,0) AS daily_sales,
                COALESCE(dd.dailyAvgTickets,0) AS daily_avg_tickets,
                COALESCE(dd.dailyDown,0) AS daily_down_payments,
                COALESCE(dd.dailyAvgDown,0) AS daily_avg_payments,
                COALESCE(dd1.dailyCollected,0) AS daily_collected_from_balances
                FROM locations l
                LEFT JOIN (
                SELECT
                    COUNT(t.patient_id) as dailyPatientsCount,
                    SUM(t.total) as dailySales,
                    AVG(t.total) as dailyAvgTickets,
                    SUM(t.amount_paid_during_office_visit) as dailyDown,
                    AVG(t.amount_paid_during_office_visit) as dailyAvgDown,
                    t.location_id
                FROM tickets t
                WHERE t.date = "' . $nowSql . '"
                AND t.revisit = 0
                GROUP BY t.location_id
                ) dd ON l.id = dd.location_id
                LEFT JOIN (
                SELECT
                    SUM(pm.amount) as dailyCollected,
                    t.location_id
                FROM tickets t
                JOIN payments pm
                ON pm.ticket_id = t.id
                AND pm.date = "' . $nowSql . '"
                AND t.revisit = 0
                AND pm.refund=0
                GROUP BY t.location_id
                ) dd1 ON l.id = dd1.location_id
                WHERE l.id IN (' . $qryLocations . ')
                GROUP BY l.location_name
                ORDER BY daily_sales DESC

            ');

      $dailyScheduleStats = DB::select('
                SELECT
                l.id,
                l.location_name,
                COALESCE(ds.dailyCountTickets,0) AS count_of_tickets,
                COALESCE(ds1.Booked,0) AS booked,
                COALESCE(ds2.Confirmed,0) AS confirm,
                COALESCE(ds2.NoShow,0) AS no_show,
                COALESCE(ds3.ticketsWithNoProducts/ds.dailyCountTickets*100,0) AS ace
                FROM locations l
                LEFT JOIN (
                SELECT
                    COUNT(t.id) as dailyCountTickets,
                    t.location_id
                FROM tickets t
                WHERE t.date = "' . $nowSql . '"
                AND t.revisit = 0
                AND t.refill = 0
                GROUP BY t.location_id
                ) ds ON l.id = ds.location_id
                LEFT JOIN (
                SELECT
                    location_id,
                    COUNT(IF(s.new_customer = 1, 1, NULL)) as Booked
                FROM schedules s
                WHERE DATE(s.created_at) = "' . $nowSql . '"
                GROUP BY location_id
                ) ds1 ON l.id = ds1.location_id
                LEFT JOIN (
                SELECT
                    s.location_id,
                    COUNT(IF(t.id IS NULL, 1, NULL)) as NoShow,
                    COUNT(IF(s.schedule_type_id = 3, 1, NULL)) as Confirmed
                FROM schedules s
                LEFT JOIN tickets t
                ON s.id = t.schedule_id
                WHERE s.date = "' . $nowSql . '"
                GROUP BY s.location_id
                ) ds2 ON l.id = ds2.location_id
                LEFT JOIN (
                SELECT
                    t.location_id,
                    COUNT(IF(tp.id IS NULL, 1, NULL)) as ticketsWithNoProducts
                FROM tickets t
                LEFT JOIN ticket_products tp
                ON t.id = tp.ticket_id
                WHERE t.date = "' . $nowSql . '"
                AND t.revisit = 0
                AND t.refill = 0
                GROUP BY location_id
                ) ds3 ON l.id = ds3.location_id
                WHERE l.id IN (' . $qryLocations . ')
            ');
    }


    //dd($dailyScheduleStats);
    $res['monthly'] =  $monthlyStats;
    $res['daily'] =  $dailyStats;
    $res['dailyScheduleStats'] =  $dailyScheduleStats;

    //info('nowSql value: ' . $nowSql . ' - qryLocationsValue: ' . $qryLocations);   

    return $res;
  }
}
