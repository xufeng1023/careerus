window.Chart = require('chart.js');

Vue.component('chart', {
    template: '<canvas id="bar-chart-canvas"></canvas>',
    props: ['dataSet'],
    data() {
        return {
            bars: {
                labels: [],
                visa: [],
                bgColor: []
            }
        }
    },
    mounted: function() {
        var self = this;

        for(var i = this.dataSet[0].year; i < this.dataSet[this.dataSet.length - 1].year; i++) {
            var missing = this.dataSet.every(val => {
                return val.year != i;
            });

            if(missing) {
                this.dataSet.push({
                    year: i,
                    number_of_visa: 0
                });
            }
        }

        this.dataSet.sort((a, b) => {
            return a.year > b.year;
        });

        this.dataSet.forEach(function(val) {
            self.bars.labels.push(val.year);
            self.bars.visa.push(val.number_of_visa);
        });
        
        let sum = self.bars.visa.reduce(function(total, num) {
            return Number(total) + Number(num);
        });

        self.bars.visa.forEach(function(val) {
            self.bars.bgColor.push('rgba(255, 99, 132, '+ val / sum +')');
        });

        var myChart = new Chart(
            document.getElementById("bar-chart-canvas").getContext('2d'),
            {
                type: 'bar',
                data: {
                    labels: self.bars.labels,
                    datasets: [{
                        label: '',
                        data: self.bars.visa,
                        backgroundColor: self.bars.bgColor,
                        borderWidth: 1
                    }]
                },
                options: {
                    legend: {
                        labels: {
                            boxWidth: 0
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return data['datasets'][0]['data'][tooltipItem['index']] + ' visa issued';
                            }
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            }
        );
    }
});


const app = new Vue({
    el: '#jobPageLeft',
    data: {
        errors: ''
    },
    methods: {
        onSubmit(e) {
            $(e.target).find('[type=submit]').attr('disabled', true).addClass('loading');
            e.preventDefault();
            let uri = e.target.getAttribute('action');
            let fd = new FormData(e.target);
            $.ajax(uri, {
                type: 'post',
                context: this,
                data: fd,
                processData: false,
                contentType: false,
                error(data) {
                    this.errors = JSON.parse(data.responseText).errors;
                },
                success(data) {
                    location.assign(data);
                },
                complete: function() {
                    $(e.target).find('[type=submit]').attr('disabled', false).removeClass('loading');
                }
            });
        },
        onChange(e) {
            let file = e.target.files[0];
            $(e.target).siblings('label').text(file.name);
        }
    }
});