<div class="fwa_calculator_container" ng-app='profitCalculatorApp'> 
    <div ng-controller="CalculatorController" ng-cloak>
        <div class='row top-row'>
            <div class="span6">
                <div class='calc_section'>
                    <div class='row'>
                        <div class="span12">
                            <label>Annual Fee-Based Revenue:</label>
                            <input style="width: 170px;" ng-model="revenueFeeBased" ng-change="drawGraph()" type="number" step="5000" >
                        </div>
                    </div>
                    <div class='row'>
                        <div class="span12">
                            <label>Annual Brokerage Revenue:</label>
                            <input style="width: 170px;" ng-model="revenueBrokerage" ng-change="drawGraph()" type="number"  step="5000">
                        </div>
                    </div>
                    <div class='row'>
                        <div class="span12 range-container">
                            <label>Projected 10 Year Growth: </label>
                            <input style="width: 170px; background: none;" ng-model="projectedGrowth" ng-change="drawGraph()" type="range" id="growth" min="0.005" value="0.10" max="1.00" step="0.005">
                            <span style="top: -3px; margin-top: 0px; font-size: 18px; position: relative; display: inline-block;">{{projectedGrowth * 100| number :1}}%</span>
                        </div>
                    </div>
                    <div class='row'>
                        <div class="span12 range-container">
                            <label>Number of States Licensed In: </label>
                            <input style="width: 170px; background: none;" ng-model="statesLicensed" ng-change="drawGraph()" type="range" id="states" min="1" value="1" max="50" step="1">
                            <span style="top: -3px; margin-top: 0px; font-size: 18px; position: relative; display: inline-block;">{{statesLicensed}}</span>
                        </div>
                    </div>
                    <div class='row'>
                        <div class="span12 range-container" >
                            <label>Total Number of Accounts (Not HH): </label>
                            <input style="width: 170px;" ng-model="numberOfAccounts" ng-change="drawGraph()" type="number" id="accounts" max="10000" step="1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="span6">
<!--                <div class='calc_section'>
                    <table>
                        <tr>
                            <th style='width: 173px;'></th>
                            <th style='width: 110px'>Total Income after<br>FICA, Payroll Taxes</th>
                            <th style='width: 110px'>Est Payout Calc after<br>FICA, Payroll Taxes</th>
                            <th style='width: 110px'>Gross Payout %</th>
                        </tr>
                        <tr>
                            <td>W-2 FWA Brand</td>
                            <td>{{ fwa_w2.total_after_payroll() | currency:"$":0 }}</td>
                            <td>{{ (fwa_w2.total_after_payroll()/totalRevenue()) * 100 |  number :1 }}%</td>
                            <td style="font-weight: bold; text-align: center; vertical-align: middle;" rowspan="3">{{ GrossPayoutT12Production()  * 100 |  number :1 }}%</td>
                        </tr>
                        <tr>
                            <td>1099 FWA Brand</td>
                            <td>{{ fwa_1099.total_after_payroll() | currency:"$":0 }}</td>
                            <td>{{ (fwa_1099.total_after_payroll()/totalRevenue()) * 100 |  number :1 }}%</td>
                            <td style="font-weight: bold;">{{ GrossPayoutT12Production()  * 100 |  number :1 }}%</td>
                        </tr>
                        <tr>
                            <td>1099 Self-Brand</td>
                            <td>{{ self_1099.total_after_payroll() | currency:"$":0 }}</td>
                            <td>{{ (self_1099.total_after_payroll()/totalRevenue()) * 100 |  number :1 }}%</td>
                            <td style="font-weight: bold;">{{ .87 * 100 |  number :1 }}%</td>
                        </tr>
                    </table>
                </div>-->
                <div class='calc_section'>
                    <table>
                        <tr>
                            <th style='width: 175px;'>FWA Net Income After…</th>
                            <th style='width: 110px'>3 Years</th>
                            <th style='width: 110px'>5 Years</th>
                            <th style='width: 110px'>10 Years</th>
                        </tr>
                        <tr>
                            <td>W-2 FWA Brand</td>
                            <td>{{ fwa_w2.projected3year() | currency:"$":0 }}</td>
                            <td>{{ fwa_w2.projected5year() | currency:"$":0 }}</td>
                            <td>{{ fwa_w2.projected10year() | currency:"$":0 }}</td>
                        </tr>
                        <tr>
                            <td>1099 FWA Brand</td>
                            <td>{{ fwa_1099.projected3year() | currency:"$":0 }}</td>
                            <td>{{ fwa_1099.projected5year() | currency:"$":0 }}</td>
                            <td>{{ fwa_1099.projected10year() | currency:"$":0 }}</td>
                        </tr>
                        <tr>
                            <td>1099 Self-Brand</td>
                            <td>{{ self_1099.projected3year() | currency:"$":0 }}</td>
                            <td>{{ self_1099.projected5year() | currency:"$":0 }}</td>
                            <td>{{ self_1099.projected10year() | currency:"$":0 }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="row" id="chart_div_wrapper">
            <h4>Net Income at FWA: 3, 5, and 10 Years</h4>
            <div id="chart_div"></div>
<!--            <div class='calc_section below-chart' style="font-size: 12px; font-weight: bold; line-height: 1.2em;">
                <h5>Total Gross Revenue: {{totalRevenue() | currency:"$":0}}</h5>
                Gross Payout based on T-12 Production: 
                <br>
                Reps Gross after %: {{ grossAfterPercent() | currency:"$":0 }}
            </div>-->
        </div>
        
<!--        <div>
            <p style="text-align: center;"><a style="cursor: pointer;" id="show_expenses_table">Customize Your Business Model</a></p>
        </div>-->
        <div class='row'>
            <div class="calc_section span12" id="expenses_table_div">
                <table class='expenses_table'>
                    <tr class="expenses_table_blue_header">
                        <th></th>
                        <th>Annl Mgd</th>
                        <th>Annl Brkrge</th>
                        <th>Total Anl Rev</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>Gross Income</td>
                        <td>{{revenueFeeBased | currency:"$":0}}</td>
                        <td>{{revenueBrokerage | currency:"$":0}}</td>
                        <td class="expenses_table_red_bg">{{totalRevenue() | currency:"$":0}}</td>
                        <td></td>
                    </tr>
                    <tr class="expenses_table_blue_header">
                        <th>Fee Description Industry-Regulatory-LPL</th>
                        <th>Annual Fees</th>
                        <th>W-2 Onsite</th>
                        <th>1099 FWA</th>
                        <th>1099 SB Off Site</th>
                    </tr>
                    <tr>
                        <td>Reps Payout  Per Affil Channel</td>
                        <td></td>
                        <td class="expenses_table_red_bg">25%</td>
                        <td class="expenses_table_red_bg">{{ GrossPayoutT12Production() * 100 |  number :1 }}%</td>
                        <td class="expenses_table_red_bg">90%</td>
                    </tr>
                    <tr>
                        <td>Reps Gross Payout in Dollars</td>
                        <td></td>
                        <td class="expenses_table_red_bg">{{ totalRevenue() * .25 |   currency:"$":0 }}</td>
                        <td class="expenses_table_red_bg">{{ GrossPayoutT12Production() * totalRevenue() | currency:"$":0 }}</td>
                        <td class="expenses_table_red_bg">{{ totalRevenue() * .90 |   currency:"$":0 }}</td>
                    </tr>
                    <tr ng-repeat="expense in expenses_mandatory">
                        <td ng-if="expense.edit == true">{{expense.label}}</td>
                        <td ng-if="expense.edit == true">{{ expense.amount.annual_fees() | number :0 }}</td>
                        <td ng-if="expense.edit == true">{{ expense.amount.fwa_w2 | number :0 }}</td>
                        <td ng-if="expense.edit == true"><input type='number' ng-model="expense.amount.fwa_1099_val"></td>
                        <td ng-if="expense.edit == true"><input type='number' ng-model="expense.amount.self_1099_val"></td>

                        <td ng-if="expense.edit == false">{{ expense.label }}</td>
                        <td ng-if="expense.edit == false">{{ expense.amount.annual_fees() | number :0 }}</td>
                        <td ng-if="expense.edit == false">{{ expense.amount.fwa_w2 | number :0 }}</td>
                        <td ng-if="expense.edit == false">{{ expense.amount.fwa_1099() | number :0 }}</td>
                        <td ng-if="expense.edit == false">{{ expense.amount.self_1099() | number :0 }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"  style="background: #8db4e2; font-weight: bold; text-align: center;">Optional Expenses</td>
                    </tr>
                    <tr ng-repeat="expense in expenses_optional">
                        <td ng-if="expense.edit == true">{{expense.label}}</td>
                        <td ng-if="expense.edit == true">{{ expense.amount.annual_fees() | number :0 }}</td>
                        <td ng-if="expense.edit == true">{{ expense.amount.fwa_w2 | number :0 }}</td>
                        <td ng-if="expense.edit == true"><input type='number' ng-model="expense.amount.fwa_1099_val"></td>
                        <td ng-if="expense.edit == true"><input type='number' ng-model="expense.amount.self_1099_val"></td>

                        <td ng-if="expense.edit == false">{{ expense.label }}</td>
                        <td ng-if="expense.edit == false">{{ expense.amount.annual_fees() | number :0 }}</td>
                        <td ng-if="expense.edit == false">{{ expense.amount.fwa_w2 | number :0 }}</td>
                        <td ng-if="expense.edit == false">{{ expense.amount.fwa_1099() | number :0 }}</td>
                        <td ng-if="expense.edit == false">{{ expense.amount.self_1099() | number :0 }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"  style="background: #8db4e2; font-weight: bold; text-align: center;">Off Site Expense Estimates</td>
                    </tr>
                    <tr ng-repeat="expense in expenses_offsite_variable">
                        <td ng-if="expense.edit == true">{{expense.label}}</td>
                        <td ng-if="expense.edit == true">{{ expense.amount.annual_fees() | number :0 }}</td>
                        <td ng-if="expense.edit == true">{{ expense.amount.fwa_w2 | number :0 }}</td>
                        <td ng-if="expense.edit == true"><input type='number' ng-model="expense.amount.fwa_1099_val"></td>
                        <td ng-if="expense.edit == true"><input type='number' ng-model="expense.amount.self_1099_val"></td>

                        <td ng-if="expense.edit == false">{{ expense.label }}</td>
                        <td ng-if="expense.edit == false">{{ expense.amount.annual_fees() | number :0 }}</td>
                        <td ng-if="expense.edit == false">{{ expense.amount.fwa_w2 | number :0 }}</td>
                        <td ng-if="expense.edit == false">{{ expense.amount.fwa_1099() | number :0 }}</td>
                        <td ng-if="expense.edit == false">{{ expense.amount.self_1099() | number :0 }}</td>
                    </tr>
                    <tr>
                        <td>Est. Annualized Operating Costs</td>
                        <td>{{ annual_fees_annualized_operating_costs() | currency:"$":0 }}</td>
                        <td>{{ fwa_w2_annualized_operating_costs() }}</td>
                        <td>{{ fwa_1099_annualized_operating_costs() | currency:"$":0 }}</td>
                        <td>{{ self_1099_annualized_operating_costs() | currency:"$":0 }}</td>
                    </tr>
                    <tr>
                        <td>Estimate Payout Calc(Aprox %)</td>
                        <td></td>
                        <td>{{ (fwa_w2.annualized_income_estimate()/totalRevenue()) * 100 |  number :1 }}%</td>
                        <td>{{ (fwa_1099.annualized_income_estimate()/totalRevenue()) * 100 |  number :1 }}%</td>
                        <td>{{ (self_1099.annualized_income_estimate()/totalRevenue()) * 100 |  number :1 }}%</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Annualized Income Estimate (Pre Tax)</td>
                        <td style="font-weight: bold;"></td>
                        <td style="font-weight: bold;">{{ fwa_w2.annualized_income_estimate() | currency:"$":0 }}</td>
                        <td style="font-weight: bold;">{{ fwa_1099.annualized_income_estimate() | currency:"$":0 }}</td>
                        <td style="font-weight: bold;">{{ self_1099.annualized_income_estimate() | currency:"$":0 }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"style="text-align: center;"><a style="cursor: pointer;" id="show_detailed_totals">Show/Hide Details</a></td>
                    </tr>
                    <tr class="show_detailed_totals_tr">
                        <td colspan="5"  style="background: #8db4e2; font-weight: bold; text-align: center;">Self Employment Tax Estimate</td>
                    </tr>
                    <tr class="show_detailed_totals_tr">
                        <td>92.35% Inc before Payroll Taxes</td>
                        <td></td>
                        <td></td>
                        <td>{{ incomeBeforePayrollTaxes(fwa_1099_annualized_operating_costs()) | currency:"$":0 }}</td>
                        <td>{{ self_incomeBeforePayrollTaxes(self_1099_annualized_operating_costs()) | currency:"$":0 }}</td>
                    </tr>
                    <tr class="show_detailed_totals_tr">
                        <td>6.2% FICA SS on first $118,500</td>
                        <td></td>
                        <td></td>
                        <td>{{ caluclateFica(incomeBeforePayrollTaxes(fwa_1099_annualized_operating_costs())) | currency:"$":0 }}</td>
                        <td>{{ caluclateFica(self_incomeBeforePayrollTaxes(self_1099_annualized_operating_costs())) | currency:"$":0 }}</td>
                    </tr>
                    <tr class="show_detailed_totals_tr">
                        <td>FICA MED 2.9% of all</td>
                        <td></td>
                        <td></td>
                        <td>{{ caluclateFicaMed(fwa_1099.annualized_income_estimate()) | currency:"$":0 }}</td>
                        <td>{{ caluclateFicaMed(self_1099.annualized_income_estimate()) | currency:"$":0 }}</td>
                    </tr>
                    <tr class="show_detailed_totals_tr">
                        <td style="font-weight: bold;">Total Income after FICA, Payroll Taxes</td>
                        <td style="font-weight: bold;"></td>
                        <td style="font-weight: bold;"></td>
                        <td style="font-weight: bold;">{{ totalAfterPayroll(fwa_1099.annualized_income_estimate(), fwa_1099_annualized_operating_costs()) | currency:"$":0 }}</td>
                        <td style="font-weight: bold;">{{ self_totalAfterPayroll(self_1099.annualized_income_estimate(), self_1099_annualized_operating_costs()) | currency:"$":0 }}</td>
                    </tr>
                    <tr class="show_detailed_totals_tr">
                        <td style="font-weight: bold;">Estimated Payout after FICA, Payroll Taxes</td>
                        <td style="font-weight: bold;"></td>
                        <td style="font-weight: bold;"></td>
                        <td style="font-weight: bold;">{{ (totalAfterPayroll(fwa_1099.annualized_income_estimate(), fwa_1099_annualized_operating_costs())/totalRevenue()) * 100 |  number :1 }}%</td>
                        <td style="font-weight: bold;">{{ (self_totalAfterPayroll(self_1099.annualized_income_estimate(), self_1099_annualized_operating_costs())/totalRevenue()) * 100 |  number :1 }}%</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>