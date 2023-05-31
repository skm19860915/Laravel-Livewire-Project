@component('mail::message')

@component('mail::table')

| Rank | Location | Monthly Sales | Monthly AVG Ticket | Monthly Down Payment | Monthly AVG Down | Monthly Patient | Monthly Collections |
|:-------------:|:-------------:|:-------------:|:-------------:|:-------------:|:-------------:|:-------------:|:-------------:|
@foreach ($monthly as $index => $m)
| {{$index += 1}} | {{$m->location_name}} | ${{number_format($m->monthly_sales,2)}} | ${{number_format($m->monthly_avg_tickets,2)}} | ${{number_format($m->monthly_down_payments,2)}} | ${{number_format($m->monthly_avg_payments,2)}} | {{$m->monthly_patient_count}} | ${{number_format($m->monthly_collected_from_balances,2)}} |
@endforeach

@endcomponent


@component('mail::table')

| Rank | Location | Daily Sales | Daily AVG Ticket | Daily Down Payment | Daily AVG  Down | Daily Patient | Daily Collections |
|:-------------:|:-------------:|:-------------:|:-------------:|:-------------:|:-------------:|:-------------:|:-------------:|
@foreach ($daily as $index => $d)
| {{$index += 1}} | {{$d->location_name}} | ${{number_format($d->daily_sales,2)}} | ${{number_format($d->daily_avg_tickets,2)}} | ${{number_format($d->daily_down_payments,2)}} | ${{number_format($d->daily_avg_payments,2)}} | {{$d->daily_patient_count}} | ${{number_format($d->daily_collected_from_balances,2)}} |
@endforeach

@endcomponent


@component('mail::table')

| Rank | Location | Appts Booked | ACE % | No Show | Confirmations |
|:-------------:|:-------------:|:-------------:|:-------------:|:-------------:|:-------------:|
@foreach ($dailyScheduleStats as $index => $d)
| {{$index += 1}} | {{$d->location_name}} | {{$d->booked}} | {{number_format($d->ace,2)}}% | {{$d->no_show}} | {{$d->confirm}} |
@endforeach


@endcomponent


@endcomponent

