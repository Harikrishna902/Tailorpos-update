<?php

use App\Http\Middleware\FrontendLoginPageMiddleware;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\UpdateMiddleware;
use Illuminate\Support\Facades\Route;

/* login */
Route::group([ 'middleware' => ['installed',UpdateMiddleware::class,FrontendLoginPageMiddleware::class,LanguageMiddleware::class]], function () {
    Route::get('/', \App\Http\Livewire\Frontend\Home::class, '__invoke')->name('frontend');
    Route::get('/offers', \App\Http\Livewire\Frontend\Offers::class, '__invoke')->name('frontend.offers');
    Route::get('/customer-login', \App\Http\Livewire\Frontend\Login::class, '__invoke')->name('frontend.login');
    Route::get('/sign-up', \App\Http\Livewire\Frontend\Signup::class, '__invoke')->name('frontend.sign-up');
    Route::get('/terms', \App\Http\Livewire\Frontend\Pages\TermsAndConditions::class, '__invoke')->name('frontend.terms-conditions');
    Route::get('/privacy-policy', \App\Http\Livewire\Frontend\Pages\PrivacyPolicy::class, '__invoke')->name('frontend.privacy-policy');
    Route::get('/contact-us', \App\Http\Livewire\Frontend\Pages\ContactUs::class, '__invoke')->name('frontend.contact-us');
    
    Route::group(['prefix' => 'profile','middleware' => 'customer'], function () {
        Route::get('/update', \App\Http\Livewire\Frontend\Profile\EditProfile::class, '__invoke')->name('frontend.edit-profile');
        Route::get('/orders', \App\Http\Livewire\Frontend\Profile\MyOrders::class, '__invoke')->name('frontend.my-orders');
        Route::get('/create-appointment', \App\Http\Livewire\Frontend\Appointment\CreateAppointment::class, '__invoke')->name('frontend.create-appointment');
    });

    Route::group(['prefix' => 'orders','middleware' => 'customer'], function () {
        Route::get('/place-order', \App\Http\Livewire\Frontend\Orders\PlaceOrder::class, '__invoke')->name('frontend.place-order');
        Route::get('/measurements', \App\Http\Livewire\Frontend\Orders\CollectMeasurements::class, '__invoke')->name('frontend.collect-measurements');
    });
});

Route::get('/login', \App\Http\Livewire\Login::class, '__invoke')->middleware(['installed',UpdateMiddleware::class])->name('login');
/* installer */
Route::get('/install', \App\Http\Livewire\Installer::class, '__invoke')->name('installer');
/* updater */
Route::get('/update', \App\Http\Livewire\Updater::class, '__invoke')->name('updater');
/* reset password */
Route::get('/reset-password/{token}', \App\Http\Livewire\ForgetPassword::class, '__invoke')->middleware(['installed',UpdateMiddleware::class])->name('reset_password');
/* admin section */
Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'installed',UpdateMiddleware::class]], function () {
    Route::get('/', \App\Http\Livewire\Admin\Dashboard::class, '__invoke')->name('admin.dashboard');
    /* settings */
    Route::group(['prefix' => 'settings/'], function () {
        Route::get('company', \App\Http\Livewire\Admin\Settings\CompanySettings::class)->name('admin.company');
        Route::get('measurement', \App\Http\Livewire\Admin\Settings\MeasurementSettings::class)->name('admin.measurement_settings');
        Route::get('branch', \App\Http\Livewire\Branch\Settings\Settings::class)->name('branch.settings');
        Route::get('invoice', \App\Http\Livewire\Admin\Settings\InvoiceSettings::class)->name('admin.invoice-settings');
        Route::get('financial-year', \App\Http\Livewire\Admin\Settings\FinancialYearSettings::class)->name('admin.financial_year');
        Route::get('master', \App\Http\Livewire\Admin\Settings\MasterSettings::class)->name('admin.master-settings');
        Route::get('mail', \App\Http\Livewire\Admin\Settings\MailSettings::class)->name('admin.mail-settings');
    });
    /* inventory */
    Route::group(['prefix' => 'inventory/'], function () {
        Route::get('product', \App\Http\Livewire\Admin\Inventory\Products::class)->name('admin.product');
    });
    /* online orders */
    Route::group(['prefix' => 'online-orders/'], function () {
        Route::get('', \App\Http\Livewire\Admin\OnlineOrders\OnlineOrders::class)->name('admin.online-orders');
        Route::get('/view/{id}', \App\Http\Livewire\Admin\OnlineOrders\ViewOnlineOrder::class)->name('admin.view-online-order');
        Route::get('/print-a4/{id}', \App\Http\Livewire\Admin\OnlineOrders\PrintOnlineOrderA4::class)->name('admin.print-online-order-a4');
        Route::get('/print/{id}', \App\Http\Livewire\Admin\OnlineOrders\PrintOnlineOrder::class)->name('admin.print-online-order');
    });
    /* online customers */
    Route::get('online-customers', \App\Http\Livewire\Admin\OnlineCustomers\OnlineCustomers::class)->name('admin.online-customers');
    /* online customers measurement */
    Route::get('online-customers/view-measurement/{id}', \App\Http\Livewire\Admin\OnlineCustomers\ViewCustomerMeasurement::class)->name('admin.online-customers-measurement');
    /* online appointments */
    Route::get('online-appointments', \App\Http\Livewire\Admin\OnlineAppointments\OnlineAppointments::class)->name('admin.online-appointments');
    /* branch */
    Route::get('branch', \App\Http\Livewire\Admin\Branches::class)->name('admin.branch');
    /* staff */
    Route::get('staff', \App\Http\Livewire\Admin\Staffs::class)->name('admin.staff');
    /* ledger */
    Route::get('ledger', \App\Http\Livewire\Admin\Ledger\Ledgers::class)->name('admin.ledger');
    /* slider */
    Route::get('sliders', \App\Http\Livewire\Admin\Sliders\Sliders::class)->name('admin.sliders');
    /* offers */
    Route::get('offers', \App\Http\Livewire\Admin\Offers\Offers::class)->name('admin.offers');
    /* measurements */
    Route::group(['prefix' => 'measurements'], function () {
        Route::get('/', \App\Http\Livewire\Admin\Measurements\Index::class)->name('admin.measurements');
        Route::get('/add', \App\Http\Livewire\Admin\Measurements\Add::class)->name('admin.measurements.add');
        Route::get('/edit/{id}', \App\Http\Livewire\Admin\Measurements\Edit::class)->name('admin.measurements.edit');
    });
    /* contact messages */
    Route::get('/contact-messages', \App\Http\Livewire\Admin\Messages\ContactMessages::class)->name('admin.contact-messages');
    /* pages */
    Route::group(['prefix' => 'pages'], function () {
        Route::get('/privacy-policy', \App\Http\Livewire\Admin\Pages\PrivacyPolicyPage::class)->name('admin.privacy-policy');
        Route::get('/terms-conditions', \App\Http\Livewire\Admin\Pages\TermsConditionsPage::class)->name('admin.terms-conditions');
    });
    /* reports */
    Route::group(['prefix' => 'reports/'], function () {
        Route::get('customer', \App\Http\Livewire\Admin\Reports\CustomerReport::class)->name('admin.report.customer');
        Route::get('daily', \App\Http\Livewire\Admin\Reports\DailyReport::class)->name('admin.report.daily');
        Route::get('day-wise', \App\Http\Livewire\Admin\Reports\DayWiseReport::class)->name('admin.report.daywise');
        Route::get('expense', \App\Http\Livewire\Admin\Reports\ExpenseReport::class)->name('admin.report.expense');
        Route::get('payments', \App\Http\Livewire\Admin\Reports\PaymentReport::class)->name('admin.report.payment');
        Route::get('sales-return', \App\Http\Livewire\Admin\Reports\SalesReturnReport::class)->name('admin.report.sales_return');
        Route::get('stock-branch', \App\Http\Livewire\Admin\Reports\StockBranchWiseReport::class)->name('admin.report.stock_branch');
        Route::get('purchase', \App\Http\Livewire\Admin\Reports\PurchaseReport::class)->name('admin.report.purchase');
        Route::get('purchase-payment', \App\Http\Livewire\Admin\Reports\PurchasePaymentReport::class)->name('admin.report.purchase_payment');
        Route::get('payments', \App\Http\Livewire\Admin\Reports\PaymentReport::class)->name('admin.report.payments');
        Route::get('sales', \App\Http\Livewire\Admin\Reports\SalesReport::class)->name('admin.report.sales');
        Route::get('tax', \App\Http\Livewire\Admin\Reports\TaxReport::class)->name('admin.report.tax');
        Route::get('stock', \App\Http\Livewire\Admin\Reports\StockReport::class)->name('admin.report.stock');
        Route::get('income', \App\Http\Livewire\Admin\Reports\IncomeReport::class)->name('admin.report.income');
        Route::get('staff', \App\Http\Livewire\Admin\Reports\StaffReport::class)->name('admin.report.staff');
        /* print section */
        Route::group(['prefix' => 'print'], function () {
            Route::get('customer/{start_date}/{end_date}/{customer_id}', \App\Http\Livewire\Admin\Reports\Prints\PrintCustomerReport::class)->name('admin.report.print_customer');
            Route::get('daily/{date}/{branch?}', \App\Http\Livewire\Admin\Reports\Prints\PrintDailyReport::class)->name('admin.report.print_daily');
            Route::get('day-wise/{start_date}/{end_date}/{branch?}', \App\Http\Livewire\Admin\Reports\Prints\PrintDayWiseReport::class)->name('admin.report.print_daywise');
            Route::get('expense/{start_date}/{end_date}/{recvia}/{branch?}', \App\Http\Livewire\Admin\Reports\Prints\PrintExpenseReport::class)->name('admin.report.print_expense');
            Route::get('income/{start_date}/{end_date}', \App\Http\Livewire\Admin\Reports\Prints\PrintIncomeReport::class)->name('admin.report.print_income');
            Route::get('payments/{start_date}/{end_date}/{recvia}/{branch?}', \App\Http\Livewire\Admin\Reports\Prints\PrintPaymentReport::class)->name('admin.report.print_payments');
            Route::get('sales/{start_date}/{end_date}/{branch?}', \App\Http\Livewire\Admin\Reports\Prints\PrintSalesReport::class)->name('admin.report.print_sales');
            Route::get('sales-return/{start_date}/{end_date}/{branch?}', \App\Http\Livewire\Admin\Reports\Prints\PrintSalesReturnReport::class)->name('admin.report.print_sales_returns');
            Route::get('stock-branch-wise/{branch?}/{search?}', \App\Http\Livewire\Admin\Reports\Prints\PrintStockBranchWiseReport::class)->name('admin.report.print_stock_branch_wise');
            Route::get('purchase/{start_date}/{end_date}', \App\Http\Livewire\Admin\Reports\Prints\PrintPurchaseReport::class)->name('admin.report.print_purchase');
            Route::get('purchase-payment/{start_date}/{end_date}/{recvia?}', \App\Http\Livewire\Admin\Reports\Prints\PrintPurchasePaymentReport::class)->name('admin.report.print_purchase_payment');
            Route::get('stock-report/{search?}', \App\Http\Livewire\Admin\Reports\Prints\PrintStockReport::class)->name('admin.report.print_stock_report');
            Route::get('staff/{start_date}/{end_date}/{staff}', \App\Http\Livewire\Admin\Reports\Prints\PrintStaffReport::class)->name('admin.report.print_staff_report');
        });
    });
    /* invoice */
    Route::get('/invoice', \App\Http\Livewire\Admin\Invoice\Invoices::class, '__invoke')->name('admin.invoice');
    Route::get('/invoice-rtl', \App\Http\Livewire\Admin\Invoice\RTLInvoice::class, '__invoke')->name('admin.invoice_rtl');
    Route::get('/invoice/print-invoice/{id}', \App\Http\Livewire\Admin\Invoice\PrintInvoice::class);
    Route::get('/invoice/print-invoice-a4/{id}', \App\Http\Livewire\Admin\Invoice\PrintInvoiceA4::class);
    /* status screen */
    Route::get('/status-screen', \App\Http\Livewire\Admin\Invoice\StatusScreen::class, '__invoke')->name('admin.status_screen');
    /* customer*/
    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', \App\Http\Livewire\Admin\Customers\Customers::class, '__invoke')->name('admin.customers');
        Route::get('view/{id}', \App\Http\Livewire\Admin\Customers\CustomerView::class, '__invoke')->name('admin.view_customer');
        Route::get('view/{id}/payments', \App\Http\Livewire\Admin\Customers\CustomerViewPayments::class, '__invoke')->name('admin.view_customer_payments');
        Route::get('view/{id}/measurement', \App\Http\Livewire\Admin\Customers\CustomerViewMeasurement::class, '__invoke')->name('admin.view_customer_measurement');
        Route::get('view/{id}/discount', \App\Http\Livewire\Admin\Customers\CustomerViewPaymentDiscount::class, '__invoke')->name('admin.view_customer_discount');
        Route::get('create', \App\Http\Livewire\Admin\Customers\CustomerCreate::class, '__invoke')->name('admin.create_customer');
        Route::get('edit/{id}', \App\Http\Livewire\Admin\Customers\CustomerEdit::class, '__invoke')->name('admin.edit_customer');
    });
    /* customer groups */
    Route::get('customer-groups', \App\Http\Livewire\Admin\Customers\CustomerGroups::class, '__invoke')->name('admin.customer_groups');
    /* expense section */
    Route::group(['prefix' => 'expense'], function () {
        Route::get('/', \App\Http\Livewire\Admin\Expense\Expenses::class, '__invoke')->name('admin.expenses');
        Route::get('/heads', \App\Http\Livewire\Admin\Expense\ExpenseHeads::class, '__invoke')->name('admin.expense_heads');
    });
    /* payments section */
    Route::group(['prefix' => 'payments'], function () {
        Route::get('/', \App\Http\Livewire\Admin\Payments\Payments::class, '__invoke')->name('admin.payments');
        Route::get('/add', \App\Http\Livewire\Admin\Payments\AddPayment::class, '__invoke')->name('admin.add_payments');
        Route::get('/print/{id}', \App\Http\Livewire\Admin\Payments\PrintVoucher::class, '__invoke')->name('admin.print_voucher');
    });
    /* purchase section */
    Route::group(['prefix' => 'purchase', 'middleware'  => 'admin-only'], function () {
        Route::get('/', \App\Http\Livewire\Admin\Purchase\Purchases::class, '__invoke')->name('admin.purchases');
        Route::get('/create', \App\Http\Livewire\Admin\Purchase\PurchaseCreate::class, '__invoke')->name('admin.purchase_create');
        Route::get('/edit/{id}', \App\Http\Livewire\Admin\Purchase\PurchaseEdit::class, '__invoke')->name('admin.purchase_edit');
        Route::get('/view/{id}', \App\Http\Livewire\Admin\Purchase\PurchaseView::class, '__invoke')->name('admin.purchase_view');
        Route::get('/suppliers', \App\Http\Livewire\Admin\Purchase\PurchaseSuppliers::class, '__invoke')->name('admin.suppliers');
        Route::get('/suppliers/view/{id}', \App\Http\Livewire\Admin\Purchase\PurchaseSuppliersView::class, '__invoke')->name('admin.suppliers_view');
        Route::get('/suppliers/view-payment/{id}', \App\Http\Livewire\Admin\Purchase\PurchaseSuppliersViewPayment::class, '__invoke')->name('admin.suppliers_viewpayment');
        Route::get('/payments', \App\Http\Livewire\Admin\Purchase\PurchasePayments::class, '__invoke')->name('admin.purchase_payments');
    });
    Route::get('/materials', \App\Http\Livewire\Admin\Purchase\PurchaseMaterialsList::class, '__invoke')->name('admin.materials');
    /* stock adjustment section */
    Route::group(['prefix' => 'stock-adjustment'], function () {
        Route::get('/', \App\Http\Livewire\Admin\Stock\StockAdjustmentList::class, '__invoke')->name('admin.stock_adjustments');
        Route::get('/add', \App\Http\Livewire\Admin\Stock\StockAdjustmentAdd::class, '__invoke')->name('admin.stock_adjustments_create');
        Route::get('/edit/{id}', \App\Http\Livewire\Admin\Stock\StockAdjustmentEdit::class, '__invoke')->name('admin.stock_adjustments_edit');
        Route::get('/view/{id}', \App\Http\Livewire\Admin\Stock\StockAdjustmentView::class, '__invoke')->name('admin.stock_adjustments_view');
    });
    /* stock transfer section */
    Route::group(['prefix' => 'stock-transfer'], function () {
        Route::get('/', \App\Http\Livewire\Admin\Stock\StockTransferList::class, '__invoke')->name('admin.stock_transfer');
        Route::get('/add', \App\Http\Livewire\Admin\Stock\StockTransferAdd::class, '__invoke')->name('admin.stock_transfer_create');
        Route::get('/edit/{id}', \App\Http\Livewire\Admin\Stock\StockTransferEdit::class, '__invoke')->name('admin.stock_transfer_edit');
        Route::get('/view/{id}', \App\Http\Livewire\Admin\Stock\StockTransferView::class, '__invoke')->name('admin.stock_transfer_view');
    });
    /* sales */
    Route::group(['prefix' => 'sales'], function () {
        Route::get('/', \App\Http\Livewire\Admin\Sales\Sales::class, '__invoke')->name('admin.sales');
        Route::get('/returns/create', \App\Http\Livewire\Admin\Sales\SalesReturns::class, '__invoke')->name('admin.sales_returns');
        Route::get('/returns/', \App\Http\Livewire\Admin\Sales\SalesReturnlist::class, '__invoke')->name('admin.sales_return_list');
        Route::get('/returns/view/{id}', \App\Http\Livewire\Admin\Sales\SalesReturnView::class, '__invoke')->name('admin.sales_return_view');
        Route::get('/returns/print/{id}', \App\Http\Livewire\Admin\Sales\PrintSalesReturn::class, '__invoke')->name('admin.sales_return_print');
        Route::get('/view/{id}', \App\Http\Livewire\Admin\Sales\SalesView::class, '__invoke')->name('admin.sales_view');
    });
    /* logout */
    Route::get('logout', \App\Http\Livewire\Admin\Logout::class)->name('admin.logout');
});
