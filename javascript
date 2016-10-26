var myApp = angular.module('myApp', ['angularUtils.directives.dirPagination']);
myApp.controller('MyController', function ($scope, $http) {
    $scope.legislators=[]
    $http({
        method: 'GET',
        url: 'getData.php',
        params: {value: 'legislator'}
    }).then(function (response) {
        $scope.legislators = response.data.results;
        $scope.getLegislators();
    });
    $scope.getLegislators=function(){
        var legislators=[]
        legislators=$scope.legislators
        $scope.users = []
        $scope.house = []
        $scope.senate = []
        for (var i = 0; i < legislators.length; i++) {
            if (legislators[i]["last_name"] != "")
                last_name = legislators[i]["last_name"]
            else
                last_name = ""
            if (legislators[i]["first_name"] != "")
                first_name = legislators[i]["first_name"]
            else
                first_name = ""
            if (legislators[i]["party"] != "")
                party = legislators[i]["party"]
            else
                party = "NA"
            if (legislators[i]["chamber"] != "")
                chamber = legislators[i]["chamber"]
            else
                chamber = "NA"
            if (legislators[i]["district"] != null)
                district = "District" + ' ' + legislators[i]["district"]
            else
                district = "NA"
            if (legislators[i]["state"] != "")
                state = legislators[i]["state"]
            else
                state = "NA"
            var name = last_name + ',' + first_name;
            if (party == "R")
                party = "r.png";
            else
                party = "d.png";
            if (chamber == "senate") {
                chamber = "Senate"
                chamber_type = "s.svg";
            }
            else {
                chamber = "House"
                chamber_type = "h.png";
            }
            $scope.users.push({
                party_name: party,
                fullname: name,
                chamber_name: chamber,
                district_name: district,
                chamber_type: chamber_type,
                state_name: state
            })
            if (chamber == "Senate") {
                $scope.senate.push({
                    party_name: party,
                    fullname: name,
                    chamber_name: chamber,
                    district_name: district,
                    chamber_type: chamber_type,
                    state_name: state
                })
            }
            if(chamber == "House"){
                $scope.house.push({
                    party_name: party,
                    fullname: name,
                    chamber_name: chamber,
                    district_name: district,
                    chamber_type: chamber_type,
                    state_name: state
                })
            }

        }

    }
})

function showDiv(id1,id2,id3){
    document.getElementById(id2).style.display="none";
    document.getElementById(id3).style.display="none";
    document.getElementById(id1).style.display="block";
}
/*function legis(){
 $(".bills").empty();}
 $("ul.nav li").click(function (e) {
 alert($(this).text());
 })*/
