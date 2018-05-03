window.Chart = require('chart.js');

Vue.component('chart', {
    template: '<canvas id="chart-canvas"></canvas>',
    props: ['dataSet', 'chartTitle'],
    data() {
        return {
            bars: {
                labels: [],
                visa: [],
                content: [],
                bgColor: []
            }
        }
    },
    mounted: function() {
        var self = this;

        this.dataSet.forEach(function(val) {
            self.bars.labels.push(val.year);
            self.bars.visa.push(val.number_of_visa);
            self.bars.content.push(val.jobs);
        });
        
        let sum = self.bars.visa.reduce(function(total, num) {
            return Number(total) + Number(num);
        });

        self.bars.visa.forEach(function(val) {
            self.bars.bgColor.push('rgba(255, 99, 132, '+ val / sum +')');
        });

        var myChart = new Chart(
            document.getElementById("chart-canvas").getContext('2d'),
            {
                type: 'bar',
                data: {
                    labels: self.bars.labels,
                    datasets: [{
                        label: self.chartTitle,
                        data: self.bars.visa,
                        content: self.bars.content,
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
                            },
                            afterLabel: function(tooltipItem, data) {
                                return data['datasets'][0]['content'][tooltipItem['index']].split(',');

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
    el: '#app',
    data: {
        errors: ''
    },
    methods: {
        onSubmit(e) {
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
                }
            });
        },
        onChange(e) {
            let file = e.target.files[0];
            $(e.target).siblings('label').text(file.name);
        }
    }
});