angular.module('valuationCalculatorApp', []) 
    .controller('CalculatorController', ['$scope', function($scope) {
        $scope.grossRevenue = 1000000;
        $scope.feeBased = 1.0;
        $scope.assetsUnderManagement = 1000000;
        $scope.totalAnnualExpensesGrossRevenueT12 = 200000;
        
        $scope.lowEstimate = function(){
            return (((($scope.feeBased * 100)*($scope.grossRevenue))*2.1) + (((100-($scope.feeBased * 100))*($scope.grossRevenue))*1))/100;
        }
        $scope.highEstimate = function(){
            return ((($scope.grossRevenue-$scope.totalAnnualExpensesGrossRevenueT12)*4)+(($scope.grossRevenue-$scope.totalAnnualExpensesGrossRevenueT12)*6))/2;
        }
        $scope.estimatedBuyoutPrice = function(){
            return ( $scope.lowEstimate() + $scope.highEstimate() )/2;
        }
        
        $scope.minimumDownpayment = function(){
            return $scope.estimatedBuyoutPrice() *0.2;
        }
        $scope.minEarnOut = function(){
            return $scope.estimatedBuyoutPrice() - $scope.minimumDownpayment();
        }
        $scope.minBuyoutTermLength = function(){
            return $scope.minEarnOut()/($scope.grossRevenue - $scope.totalAnnualExpensesGrossRevenueT12);
        }
        
        
        $scope.maximumDownpayment = function(){
            return $scope.estimatedBuyoutPrice() *0.36;
        }
        $scope.maxEarnOut = function(){
            return $scope.estimatedBuyoutPrice() - $scope.maximumDownpayment();
        }
        $scope.maxBuyoutTermLength = function(){
            return $scope.maxEarnOut()/($scope.grossRevenue - $scope.totalAnnualExpensesGrossRevenueT12);
        }
        
        $scope.recommendedDownpayment = function(){
            return $scope.estimatedBuyoutPrice() *0.30;
        }
        $scope.recommendedEarnOut = function(){
            return $scope.estimatedBuyoutPrice() - $scope.recommendedDownpayment();
        }
        $scope.recommendedTermLength = function(){
            return $scope.recommendedEarnOut()/($scope.grossRevenue - $scope.totalAnnualExpensesGrossRevenueT12);
        }
        
}]);