angular.module('profitCalculatorApp', []) 
    .controller('CalculatorController', ['$scope', function($scope) {
        $scope.revenueFeeBased = 650000;
        $scope.revenueBrokerage = 100000;
        $scope.projectedGrowth = .10;
        $scope.statesLicensed = 4;
        $scope.numberOfAccounts = 200;
        $scope.totalRevenue = function(){
            return $scope.revenueFeeBased + $scope.revenueBrokerage;
        }
        
        $scope.annual_fees_annualized_operating_costs = function(){
            total = 0;
            for(expense in $scope.expenses_mandatory){
                total += $scope.expenses_mandatory[expense].amount.annual_fees()
            }
            for(expense in $scope.expenses_optional){
                total += $scope.expenses_optional[expense].amount.annual_fees()
            }
            for(expense in $scope.expenses_offsite_variable){
                total += $scope.expenses_offsite_variable[expense].amount.annual_fees()
            }
            return total;
        }
        $scope.fwa_w2_annualized_operating_costs = function(){
            total = 0;
            for(expense in $scope.expenses_mandatory){
                total += $scope.expenses_mandatory[expense].amount.fwa_w2
            }
            for(expense in $scope.expenses_optional){
                total += $scope.expenses_optional[expense].amount.fwa_w2
            }
            for(expense in $scope.expenses_offsite_variable){
                total += $scope.expenses_offsite_variable[expense].amount.fwa_w2
            }
            return total;
        }
        $scope.fwa_1099_annualized_operating_costs = function(){
            total = 0;
            for(expense in $scope.expenses_mandatory){
                if($scope.expenses_mandatory[expense].edit == true){
                    total += $scope.expenses_mandatory[expense].amount.fwa_1099_val;
                }else{
                    total += $scope.expenses_mandatory[expense].amount.fwa_1099()
                }
            }
            for(expense in $scope.expenses_optional){
                if($scope.expenses_optional[expense].edit == true){
                    total += $scope.expenses_optional[expense].amount.fwa_1099_val;
                }else{
                    total += $scope.expenses_optional[expense].amount.fwa_1099()
                }
            }
            for(expense in $scope.expenses_offsite_variable){
                if($scope.expenses_offsite_variable[expense].edit == true){
                    total += $scope.expenses_offsite_variable[expense].amount.fwa_1099_val;
                }else{
                    total += $scope.expenses_offsite_variable[expense].amount.fwa_1099()
                }
            }
            return total;
        }
        $scope.self_1099_annualized_operating_costs = function(){
            total = 0;
            for(expense in $scope.expenses_mandatory){
                if($scope.expenses_mandatory[expense].edit == true){
                    total += $scope.expenses_mandatory[expense].amount.self_1099_val;
                }else{
                    total += $scope.expenses_mandatory[expense].amount.self_1099()
                }
            }
            for(expense in $scope.expenses_optional){
                if($scope.expenses_optional[expense].edit == true){
                    total += $scope.expenses_optional[expense].amount.self_1099_val;
                }else{
                    total += $scope.expenses_optional[expense].amount.self_1099()
                }
            }
            for(expense in $scope.expenses_offsite_variable){
                if($scope.expenses_offsite_variable[expense].edit == true){
                    total += $scope.expenses_offsite_variable[expense].amount.self_1099_val;
                }else{
                    total += $scope.expenses_offsite_variable[expense].amount.self_1099()
                }
            }
            return total;
        }
        
        $scope.expenses_mandatory = [
            {
                label:  "FINRA Anl Fees (Renewal,Assmnt,Brch,Offce)",
                amount: {
                    annual_fees: function(){ return 270},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 270},
                    self_1099: function(){ return 270}
                },
                edit: false,
                type: "mandatory"
            },
            {
                label:  "FINRA Assesment (.0023 GC)",
                amount: {
                    annual_fees: function(){ return $scope.totalRevenue() * 0.0023},
                    fwa_w2: 0,
                    fwa_1099: function(){ return $scope.totalRevenue() * 0.0023},
                    self_1099: function(){ return $scope.totalRevenue() * 0.0023}
                },
                edit: false,
                type: "mandatory"
            },
            {
                label:  "SIPC Assesment (.00188 GC )",
                amount: {
                    annual_fees: function(){ return $scope.totalRevenue() * 0.00188},
                    fwa_w2: 0,
                    fwa_1099: function(){ return $scope.totalRevenue() * 0.00188},
                    self_1099:  function(){ return $scope.totalRevenue() * 0.00188}
                },
                edit: false,
                type: "mandatory"
            },
            {
                label:  "NASDAQ Renewal",
                amount: {
                    annual_fees: function(){ return 55},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 55},
                    self_1099:  function(){ return 55}
                },
                edit: false,
                type: "mandatory"
            },
            {
                label:  "State Renewal (Averaged $50ea)",
                amount: {
                    annual_fees: function(){ return $scope.statesLicensed * 50},
                    fwa_w2: 0,
                    fwa_1099: function(){ return $scope.statesLicensed * 50},
                    self_1099:  function(){ return $scope.statesLicensed * 50}
                },
                edit: false,
                type: "mandatory"
            },
            {
                label:  "Compliance Fee @$900",
                amount: {
                    annual_fees: function(){ return 900;},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 900;},
                    self_1099:  function(){ return 900;},
                },
                edit: false,
                type: "mandatory"
            },
            {
                label:  "Errors and Omision Insurance",
                amount: {
                    annual_fees: function(){ return 3900},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 3900},
                    self_1099:  function(){ return 3900},
                },
                edit: false,
                type: "mandatory"
            },
            {
                label:  "Errors and Omission Prgrm Fee",
                amount: {
                    annual_fees: function(){ return 3600},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 3600},
                },
                edit: false,
                type: "mandatory"
            },
            {
                label:  "Bonding Fee",
                amount: {
                    annual_fees: function(){ return 120},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 120},
                },
                edit: false,
                type: "mandatory"
            },
            {
                label:  "LPL Resource fee ( Anl Renewal/LPL Affil Fee)",
                amount: {
                    annual_fees: function(){ return 2200},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 2200},
                    self_1099:  function(){ return 2200},
                },
                edit: false,
                type: "mandatory"
            },
            {
                label:  "LPL Core Tech Fees (Includes CRM)",
                amount: {
                    annual_fees: function(){ return 2200},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 900},
                    self_1099:  function(){ return 2200},
                },
                edit: false,
                type: "mandatory"
            },
            {
                label:  "Home Office Supervision",
                amount: {
                    annual_fees: function(){ return 5000},
                    fwa_w2: 0,
                    fwa_1099: function(){return  0},
                    self_1099:  function(){ return 5000},
                },
                edit: false,
                type: "mandatory"
            },
            {
                label:  "Enhanced Trading, Basic Perf Reporting",
                amount: {
                    annual_fees: function(){ return 2400},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 2400},
                    self_1099:  function(){ return 2400},
                },
                edit: false,
                type: "mandatory"
            }
        ];
        $scope.expenses_optional = [
            {
                label:  "Goals Based Financial Planning Software",
                amount: {
                    annual_fees: function(){ return 1600},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 0},
                    fwa_1099_val: 0,
                    self_1099_val: 0
                },
                edit: true,
                type: "optional"
            },
            {
                label:  "Emoney or Money Guide Pro",
                amount: {
                    annual_fees: function(){ return 3600},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 0},
                    fwa_1099_val: 0,
                    self_1099_val: 0
                },
                edit: true,
                type: "optional"
            },
            {
                label:  "Opt Deluxe Trading and Reporting",
                amount: {
                    annual_fees: function(){ return $scope.numberOfAccounts * 20},
                    fwa_w2: 0,
                    fwa_1099_val: 0,
                    self_1099_val: 0
                },
                edit: true,
                type: "optional"
            },
            {
                label:  "Opt Portfolio Mgr and Review Tool",
                amount: {
                    annual_fees: function(){ return 1800},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 0},
                    fwa_1099_val: 0,
                    self_1099_val: 0
                },
                edit: true,
                type: "optional"
            },
            {
                label:  "Opt Trade and Rebalance Tool",
                amount: {
                    annual_fees: function(){ return 3600},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 0},
                    fwa_1099_val: 0,
                    self_1099_val: 0
                },
                edit: true,
                type: "optional"
            },
            {
                label:  "OptEnhanced Perf Reporting",
                amount: {
                    annual_fees: function(){ return 1440},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 0},
                    fwa_1099_val: 0,
                    self_1099_val: 0
                },
                edit: true,
                type: "optional"
            },
            {
                label:  "OptThompson One Sales Solution",
                amount: {
                    annual_fees: function(){ return 3072},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 0},
                    fwa_1099_val: 0,
                    self_1099_val: 0
                },
                edit: true,
                type: "optional"
            },
            {
                label:  "Opt Research Tools S&P",
                amount: {
                    annual_fees: function(){ return 3840},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 0},
                    fwa_1099_val: 0,
                    self_1099_val: 0
                },
                edit: true,
                type: "optional"
            }
        ];
        $scope.expenses_offsite_variable = [
            {
                label:  "Rent and CAM fees",
                amount: {
                    annual_fees: function(){ return 24000},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 24000},
                    fwa_1099_val: 0,
                    self_1099_val: 24000
                },
                edit: true,
                type: "offsite_variable"
            },
            {
                label:  "Utilities",
                amount: {
                    annual_fees: function(){ return 1500},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 1500},
                    fwa_1099_val: 0,
                    self_1099_val: 1500
                },
                edit: true,
                type: "offsite_variable"
            },
            {
                label:  "Phone",
                amount: {
                    annual_fees: function(){ return 2400},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 2400},
                    fwa_1099_val: 0,
                    self_1099_val: 2400
                },
                edit: true,
                type: "offsite_variable"
            },
            {
                label:  "Computers (Assumes 36 month life)",
                amount: {
                    annual_fees: function(){ return 1200},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 1200},
                    fwa_1099_val: 0,
                    self_1099_val: 1200
                },
                edit: true,
                type: "offsite_variable"
            },
            {
                label:  "IT Expenses",
                amount: {
                    annual_fees: function(){ return 1200},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 1200},
                    fwa_1099_val: 0,
                    self_1099_val: 1200
                },
                edit: true,
                type: "offsite_variable"
            },
            {
                label:  "Technology Exp (printers,Fax,Software)",
                amount: {
                    annual_fees: function(){ return 1400},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 1400},
                    fwa_1099_val: 0,
                    self_1099_val: 1400
                },
                edit: true,
                type: "offsite_variable"
            },
            {
                label:  "Office Supplies",
                amount: {
                    annual_fees: function(){ return 2400},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 2400},
                    fwa_1099_val: 0,
                    self_1099_val: 2400
                },
                edit: true,
                type: "offsite_variable"
            },
            {
                label:  "Staffing Expenses (FICA,WH,UC,WC)",
                amount: {
                    annual_fees: function(){ return 46750},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 46750},
                    fwa_1099_val: 0,
                    self_1099_val: 46750
                },
                edit: true,
                type: "offsite_variable"
            },
            {
                label:  "Basic Marketing (Biz cards,Website etc)",
                amount: {
                    annual_fees: function(){ return 3600},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 3600},
                    fwa_1099_val: 0,
                    self_1099_val: 3600
                },
                edit: true,
                type: "offsite_variable"
            },
            {
                label:  "Accounting and Legal",
                amount: {
                    annual_fees: function(){ return 2000},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 2000},
                    fwa_1099_val: 0,
                    self_1099_val: 2000
                },
                edit: true,
                type: "offsite_variable"
            },
            {
                label:  "Business Insurances",
                amount: {
                    annual_fees: function(){ return 1800},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 1800},
                    fwa_1099_val: 0,
                    self_1099_val: 1800
                },
                edit: true,
                type: "offsite_variable"
            },
            {
                label:  "Lost Production @ $100 hr x 520 hr Annl",
                amount: {
                    annual_fees: function(){ return 52000},
                    fwa_w2: 0,
                    fwa_1099: function(){ return 0},
                    self_1099:  function(){ return 0},
                    fwa_1099_val: 0,
                    self_1099_val: 0
                },
                edit: true,
                type: "offsite_variable"
            }
        ];
        
        $scope.GrossPayoutT12Production = function(){
            totalRevenueOut = $scope.totalRevenue();
            if(totalRevenueOut > 999999){
                return 0.80;
            }else if(totalRevenueOut > 749999){
                return 0.70;
            }else if(totalRevenueOut > 499999){
                return 0.60;
            }else if(totalRevenueOut > 249999){
                return 0.50;
            }else{
                return 0.40;
            }
        }
        
        $scope.grossAfterPercent = function(){
            return $scope.GrossPayoutT12Production() * $scope.totalRevenue();
        }
        
//        $scope.calculateComparisons = function(){
            $scope.fwa_w2 = {
                annualized_income_estimate:  function(){
                    return $scope.totalRevenue() * .25 
                },
                total_after_payroll: function(){
                    annualized_income_estimate = $scope.fwa_w2.annualized_income_estimate();
                    return annualized_income_estimate;
                },
                projected3year: function(){
                    annual_estimate = $scope.fwa_w2.annualized_income_estimate();
                    growth = 1 + parseFloat($scope.projectedGrowth);
                    return getProjectedEstimate(3, annual_estimate, growth);
//                    +$B17+$B17*(1+$C$7)+$B17*(1+$C$7)^2
//                    return annual_estimate + (growth * annual_estimate) + (annual_estimate * Math.pow(growth, 2));
                },
                projected5year: function(){
                    annual_estimate = $scope.fwa_w2.annualized_income_estimate();
                    growth = 1 + parseFloat($scope.projectedGrowth);
                    return getProjectedEstimate(5, annual_estimate, growth);
                },
                projected10year: function(){
                    annual_estimate = $scope.fwa_w2.annualized_income_estimate();
                    growth = 1 + parseFloat($scope.projectedGrowth);
                    return getProjectedEstimate(10, annual_estimate, growth);
                },
            }
            $scope.fwa_1099 = {
                annualized_income_estimate: function(){ 
                    gross =  $scope.GrossPayoutT12Production() * $scope.totalRevenue();
                    return gross - $scope.fwa_1099_annualized_operating_costs(gross);
                },
                total_after_payroll: function(){
                    gross = $scope.grossAfterPercent();
                    annualized_operating_costs = $scope.fwa_1099_annualized_operating_costs(gross);
                    annualized_income_estimate = $scope.fwa_1099.annualized_income_estimate();
                    beforePayroll = $scope.incomeBeforePayrollTaxes(annualized_operating_costs);
                    FicaSS = $scope.caluclateFica(beforePayroll);
                    FicaMed = $scope.caluclateFicaMed(annualized_income_estimate)
                    return annualized_income_estimate - FicaMed - FicaSS;
                },
                projected3year: function(){
                    annual_estimate = $scope.fwa_1099.annualized_income_estimate();
                    growth = 1 + parseFloat($scope.projectedGrowth);
                    return getProjectedEstimate(3, annual_estimate, growth);
                },
                projected5year: function(){
                    annual_estimate = $scope.fwa_1099.annualized_income_estimate();
                    growth = 1 + parseFloat($scope.projectedGrowth);
                    return getProjectedEstimate(5, annual_estimate, growth);
                },
                projected10year: function(){
                    annual_estimate = $scope.fwa_1099.annualized_income_estimate();
                    growth = 1 + parseFloat($scope.projectedGrowth);
                    return getProjectedEstimate(10, annual_estimate, growth);
                }
            }
            $scope.self_1099 = {
                annualized_income_estimate: function(){
                    gross =  $scope.totalRevenue() * .90;
                    return gross - $scope.self_1099_annualized_operating_costs(gross)
                },
                total_after_payroll: function(){
                    gross =  $scope.totalRevenue() * .90;
                    annualized_operating_costs = $scope.self_1099_annualized_operating_costs(gross);
                    annualized_income_estimate = $scope.self_1099.annualized_income_estimate();
                    
                    beforePayroll = (gross - annualized_operating_costs) * 0.9235;
                    FicaSS = $scope.caluclateFica(beforePayroll);
                    FicaMed = $scope.caluclateFicaMed(annualized_income_estimate)
                    return annualized_income_estimate - FicaMed - FicaSS;
                },
                 projected3year: function(){
                    annual_estimate = $scope.self_1099.annualized_income_estimate();
                    growth = 1 + parseFloat($scope.projectedGrowth);
                    return getProjectedEstimate(3, annual_estimate, growth);
                },
                projected5year: function(){
                    annual_estimate = $scope.self_1099.annualized_income_estimate();
                    growth = 1 + parseFloat($scope.projectedGrowth);
                    return getProjectedEstimate(5, annual_estimate, growth);
                },
                projected10year: function(){
                    annual_estimate = $scope.self_1099.annualized_income_estimate();
                    growth = 1 + parseFloat($scope.projectedGrowth);
                    return getProjectedEstimate(10, annual_estimate, growth);
                }
            }
//        }
        
        $scope.self_incomeBeforePayrollTaxes = function(){
            gross =  $scope.totalRevenue() * .90;
            annualized_operating_costs = $scope.self_1099_annualized_operating_costs(gross);
            return (gross - annualized_operating_costs) * 0.9235;
        }
        
        $scope.incomeBeforePayrollTaxes = function(annualized_operating_costs){
            gross = $scope.grossAfterPercent();
            return (gross - annualized_operating_costs) * 0.9235;
        }
        
        $scope.caluclateFica = function(beforePayroll){
            ficaSS = 0;
            if(beforePayroll > 118500){
                ficaSS = 118500 * 0.062;
            }else{
                ficaSS = beforePayroll * 0.062;
            }
            return ficaSS;
        }
        
        $scope.caluclateFicaMed = function(annualized_income_estimate){
            return  annualized_income_estimate * 0.029;
        }
        
        $scope.self_totalAfterPayroll = function(annualized_income_estimate, annualized_operating_costs){
            beforePayroll = $scope.self_incomeBeforePayrollTaxes(annualized_operating_costs);
            FicaSS = $scope.caluclateFica(beforePayroll);
            FicaMed = $scope.caluclateFicaMed(annualized_income_estimate)
            return beforePayroll - FicaMed - FicaSS;
         }
         
        $scope.totalAfterPayroll = function(annualized_income_estimate, annualized_operating_costs){
            beforePayroll = $scope.incomeBeforePayrollTaxes(annualized_operating_costs);
            FicaSS = $scope.caluclateFica(beforePayroll);
            FicaMed = $scope.caluclateFicaMed(annualized_income_estimate);
            return beforePayroll - FicaMed - FicaSS;
         }
        
        google.charts.load('current', {packages: ['corechart', 'line']});
        google.charts.setOnLoadCallback(initGraph);
        var chart = {};
        var data = {};
        function initGraph(){
            $scope.drawGraph();
        }
        $scope.drawGraph = function(){
            chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            data = google.visualization.arrayToDataTable([
                ['Year', 'W-2 FWA Brand', '1099 FWA Brand', '1099 Self Brand'],
                ['Year 3', $scope.fwa_w2.projected3year(), $scope.fwa_1099.projected3year(), $scope.self_1099.projected3year()],
                ['Year 5', $scope.fwa_w2.projected5year(), $scope.fwa_1099.projected5year(), $scope.self_1099.projected5year()],
                ['Year 10', $scope.fwa_w2.projected10year(), $scope.fwa_1099.projected10year(), $scope.self_1099.projected10year()]
            ]);
            var options = {
                lineWidth: 3,
//                titlePosition: 'none',
//                title: "Net Income at FWA: 3, 5, and 10 Years",
//                titleTextStyle: {
//                    fontSize: 18,
//                    
//                },
                height: 350,
                width: 1000,
                chartArea: {
                    width: '75%',
                    height: '75%'
                },
                pointSize: 9,
                legend: {
                    position: "bottom",
                    textStyle: {fontSize: 14}
                },
                hAxis: {
//                  title: 'Time',
                  textStyle : {
                      fontSize: 20
                  }
                },
                vAxis: {
                  title: 'Projected Net Income',
                  format: 'currency',
                  gridlines: {
                      count: 11
                  },
                  textStyle : {
                      fontSize: 13
                  }
                }
            };
            chart.draw(data, options);
        };
        
        
}]);

function getProjectedEstimate(years, annual_estimate, growth){
    total = annual_estimate; // year 1
    for(i=(years-1); i>0; i--){
        if(i>1){
            total += annual_estimate * Math.pow(growth, i);
        }else{
            total += annual_estimate * growth;
        }
    }
    return total;
}

jQuery(document).ready(function(){
    jQuery("#show_expenses_table").click(function(){
        if ( jQuery("#expenses_table_div").css('display') == 'none' ){
            jQuery("#expenses_table_div").stop();
            jQuery("#expenses_table_div").slideDown();
        }else{
            jQuery("#expenses_table_div").stop();
            jQuery("#expenses_table_div").slideUp();
        }
    });
    jQuery("#show_detailed_totals").click(function(){
        jQuery(".show_detailed_totals_tr").each(function(){
            if ( jQuery(this).css('display') == 'none' ){
                jQuery(this).show();
            }else{
                jQuery(this).hide();
            }
        });
    });
});