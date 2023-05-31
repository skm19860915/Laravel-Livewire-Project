const mix = require('laravel-mix');

mix
  .js('resources/js/app.js', 'public/js')
  .js('resources/js/patients.js', 'public/js')
  .js('resources/js/locations.js', 'public/js')
  .js('resources/js/emails.js', 'public/js')
  .js('resources/js/schedule_type.js', 'public/js')
  .js('resources/js/schedule.js', 'public/js')
  .js('resources/js/products.js', 'public/js')
  .js('resources/js/services.js', 'public/js')
  .js('resources/js/tickets.js', 'public/js')
  .js('resources/js/tickets-filter.js', 'public/js')
  .js('resources/js/form_table.js', 'public/js')
  .js('resources/js/payment.js', 'public/js')
  .js('resources/js/pricing.js', 'public/js')
  .js('resources/js/receivable.js', 'public/js')
  .js('resources/js/history.js', 'public/js')
  .js('resources/js/communication.js', 'public/js')
  .js('resources/js/exportslog.js', 'public/js')
  .js('resources/js/dashboard.js', 'public/js')
  .js('resources/js/report.finance.js', 'public/js')
  .js('resources/js/report.sales-by-product.js', 'public/js')
  .js('resources/js/report.marketing-trend.js', 'public/js')
  .js('resources/js/report.marketing.js', 'public/js')
  .js('resources/js/marketing-sources.js', 'public/js')
  .js('resources/js/bootbox.js', 'public/js')
  .js('resources/js/users.js', 'public/js')
  .js('resources/js/zingle.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .sourceMaps()
  //  .browserSync({
  //   proxy: 'pryapusnew.local',
  //   files: [
  //     'app/**/*',
  //     'routes/**/*',
  //     'resources/**/*',
  //   ]
  // })
  .extract()
  .version();


// if (mix.inProduction()) {
//   mix.version();
// }
