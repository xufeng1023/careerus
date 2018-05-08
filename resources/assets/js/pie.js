Vue.component('pie-chart', {
    template: '<canvas id="pie-chart-canvas"></canvas>',
    props: ['dataSet'],
    data() {
        return {
            bars: {
                labels: [],
                numbers: [],
                bgColor: []
            }
        }
    },
    mounted: function() {
        var self = this;

        let dataArray = this.dataSet.split(',');

        dataArray.forEach(val => {
            var matches = val.replace(/\r\n/, '').match(/([a-zA-Z ]+)\(([\d]+)\)/);
            for(var prop in matches) {
                if(prop == 1) self.bars.labels.push(matches[prop]);
                if(prop == 2) self.bars.numbers.push(matches[prop]);
            }
            
        });

        let sum = self.bars.numbers.reduce(function(total, num) {
            return Number(total) + Number(num);
        });

        self.bars.numbers.forEach(function(val) {
            self.bars.bgColor.push('rgba(54, 162, 235, '+ val / sum +')');
        });

        var myChart = new Chart(
            document.getElementById("pie-chart-canvas").getContext('2d'),
            {
                type: 'pie',
                data: {
                    labels: self.bars.labels,
                    datasets: [{
                        data: self.bars.numbers,
                        backgroundColor: self.bars.bgColor,
                        borderWidth: 1
                    }]
                }
            }
        );
    }
});

new Vue({el: '#jobPageRight'});