<div class="fwa_calculator_container valuation_calculator" ng-app='valuationCalculatorApp'>
    <div ng-controller="CalculatorController" ng-cloak>
        <div class='row top-row'>
            <div class="span12">
                <div class='calc_section'>
                   <div class='row'>
                        <div class="span12">
                            <label>Gross Revenue: </label>
                            <input style="width: 170px;" ng-model="grossRevenue" type="number" step="5000" >
                        </div>
                    </div>
                    <div class='row'>
                        <div class="span12 range-container">
                            <label>Fee Based %: </label>
                            <input style="width: 170px; background: none;" ng-model="feeBased" type="range" id="growth" min="0.005" value="0.10" max="1.00" step="0.005">
                            <span style="top: -3px; margin-top: 0px; font-size: 18px; position: relative; display: inline-block;">{{feeBased * 100| number :1}}%</span>
                        </div>
                    </div>
                    <div class='row'>
                        <div class="span12">
                            <label>Assets Under Management: </label>
                            <input style="width: 170px;" ng-model="assetsUnderManagement" type="number"  step="5000">
                        </div>
                    </div>
                    
                    <div class='row'>
                        <div class="span12">
                            <label>Total Annual Expenses Gross Revenue T12: </label>
                            <input style="width: 170px;" ng-model="totalAnnualExpensesGrossRevenueT12" type="number" step="5000" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--        B3 = grossRevenue
        B4 = feeBased
        B5 = assetsUnderManagement
        B6 = totalAnnualExpensesGrossRevenueT12-->
        <div class='row'>
            <div class="span12">
                <div class='calc_section table_layout' style='display: table;'>
                    <div class='row table_row_layout' style='display: table-row;'>
                        <div style='display: table-cell; background: #d1ab66;'>Estimated Buyout Price:</div>
                        <div style='display: table-cell;'>{{ estimatedBuyoutPrice() | currency:"$":0 }}</div>
                    </div>
                    <div class='row table_row_layout' style='display: table-row;'>
                        <div style='display: table-cell;'>Low Estimate:</div>
                        <div style='display: table-cell;'>{{ lowEstimate() | currency:"$":0 }}</div>
                    </div>
                    <div class='row table_row_layout' style='display: table-row;'>
                        <div style='display: table-cell;'>High Estimate: </div>
                        <div style='display: table-cell;'>{{ highEstimate() | currency:"$":0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class='row'>
            <div class="span12">
                <div class='calc_section table_layout downpayments_table' style='display: table;'>
                    <div class='row table_row_layout first' style='display: table-row;'>
                        <div style='display: table-cell;'>Downpayments</div>
                        <div style='display: table-cell;'>Downpayment</div>
                        <div style='display: table-cell;'>Earn Out</div>
                        <div style='display: table-cell;'>Practice Buyout Term Length</div>
                    </div>
                    <div class='row table_row_layout' style='display: table-row;'>
                        <div style='display: table-cell;'>Minimum Downpayment (20%):</div>
                        <div style='display: table-cell;'>{{ minimumDownpayment() | currency:"$":0 }}</div>
                        <div style='display: table-cell;'>{{ minEarnOut() | currency:"$":0 }}</div>
                        <div style='display: table-cell;'>{{ minBuyoutTermLength() | number:0 }}</div>
                    </div>
                    <div class='row table_row_layout' style='display: table-row;'>
                        <div style='display: table-cell;'>Maximum Downpayment (36%):</div>
                        <div style='display: table-cell;'>{{ maximumDownpayment() | currency:"$":0 }}</div>
                        <div style='display: table-cell;'>{{ maxEarnOut() | currency:"$":0 }}</div>
                        <div style='display: table-cell;'>{{ maxBuyoutTermLength() | number:0 }}</div>
                    </div>
                    <div class='row table_row_layout' style='display: table-row;'>
                        <div style='display: table-cell;'>Recommended Downpayment (30%):</div>
                        <div style='display: table-cell;'>{{ recommendedDownpayment() | currency:"$":0 }}</div>
                        <div style='display: table-cell;'>{{ recommendedEarnOut() | currency:"$":0 }}</div>
                        <div style='display: table-cell;'>{{ recommendedTermLength() | number:0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>