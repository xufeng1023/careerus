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

        var other = 0;

        this.dataSet.split(',').forEach( (val, index) => {
            var matches = val.replace(/\r\n/, '').match(/([a-zA-Z ]*\-?[a-zA-Z ]+)\(([\d]+)\)/);

            if(!matches) return;
            
            if(index > 2) other += Number(matches[2]);
            
            else {
                self.bars.labels.push(matches[1]);
                self.bars.numbers.push(matches[2]);
            }
        });

        if(other > 0) {
            self.bars.labels.push('other');
            self.bars.numbers.push(other);
        }

        if(self.bars.numbers.length) {
            var sum = self.bars.numbers.reduce(function(total, num) {
                return Number(total) + Number(num);
            });
        }
        
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