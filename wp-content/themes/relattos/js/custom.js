/*var data_chart_1 = {
    labels: [
        'Falta leve',
        'Falta grave (subst. ilícitas)',
        'Falta gravíssima',
        'Falta grave',
        'Conversa c/ responsável',
    ],
    datasets: [
        {
            label: 'Tipos de Ações',
            data: [25, 20, 10, 11, 13],
            backgroundColor: [
                'rgb(60, 232, 142)', 'rgb(255, 165, 0)', 'rgb(255, 0, 0)', 'rgb(255, 255, 0)', 'rgb(173, 216, 230)'
            ],
            hoverOffset: 4
        },
    ]
};

chart = new Chart('chart-1', {
    type: 'doughnut',
    data: data_chart_1,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: true,
        }
    }
});

var data_chart_2 = {
	labels: [
		"MATUTINO","VESPERTINO","NOTURNO",],
    datasets: [
		{	
			label: 'Falta Leve',
			backgroundColor: ['rgb(60, 232, 142)','rgb(60, 232, 142)','rgb(60, 232, 142)','rgb(60, 232, 142)','rgb(60, 232, 142)','rgb(60, 232, 142)','rgb(60, 232, 142)','rgb(60, 232, 142)','rgb(60, 232, 142)','rgb(60, 232, 142)','rgb(60, 232, 142)','rgb(60, 232, 142)'],
			borderColor: ['rgb(60, 232, 142, 1)','rgb(60, 232, 142, 1)','rgb(60, 232, 142, 1)','rgb(60, 232, 142, 1)','rgb(60, 232, 142, 1)','rgb(60, 232, 142, 1)','rgb(60, 232, 142, 1)','rgb(60, 232, 142, 1)','rgb(60, 232, 142, 1)','rgb(60, 232, 142, 1)','rgb(60, 232, 142, 1)','rgb(60, 232, 142, 1)'],
			data: ["4","5",],
		},
    	{
    		label: 'Falta Grave Subst. Ilícitas',
			backgroundColor: ['rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))'],
			borderColor: ['rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))','rgb(255, 165, 0))'],
			data: ["6","7",],
	    },
	    {
	    	label: 'Falta gravíssima',
			backgroundColor: ['rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)'],
			borderColor: ['rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)','rgb(255, 0, 0)'],
			data: ["8","9",],
	    },
	    {
	    	label: 'Falta grave',
			backgroundColor: ['rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)'],
			borderColor: ['rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)','rgb(255, 255, 0)'],
			data: ["10","11",],
	    },
    ]
};
chart = new Chart('chart-2', {
    type: 'bar',
    data: data_chart_2,
    options: {
    	scales: {
            xAxes: [{
                stacked: false
            }],
            yAxes: [{
                stacked: false
            }],
            beginAtZero: true
        },
        responsive: true,
		maintainAspectRatio: false,
		legend: {
            display: true,
            position: 'bottom',
            boxWidth: 0,
        }
    }
});

var data_chart_3 = {
    labels: ["7º A","8º B","6º B","7º B","6º D"],
    datasets: [
        {
            label: 'Ações por turma',
            backgroundColor: ['rgb(60, 232, 142)', 'rgb(255, 165, 0)', 'rgb(255, 0, 0)', 'rgb(255, 255, 0)', 'rgb(173, 216, 230)','rgb(255, 22, 167)' ],
            borderColor: ['rgb(60, 232, 142)', 'rgb(255, 165, 0)', 'rgb(255, 0, 0)', 'rgb(255, 255, 0)', 'rgb(173, 216, 230)','rgb(255, 22, 167)' ],
            data: [11,17,15,25,20,16],
        },
    ]
};

chart = new Chart('chart-3', {
    type: 'bar',
    data: data_chart_3,
    options: {
        scales: {
            xAxes: [{
                stacked: false
            }],
            yAxes: [{
                stacked: false
            }],
        },
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false,
            position: 'bottom',
            boxWidth: 0,
        }
    }
});


var data_chart_4 = {
    labels: ["Matutino","Vespertino","Noturno",],
    datasets: [
        {
            label: 'Ações por Autor',
            backgroundColor: ['rgb(60, 232, 142)', 'rgb(255, 165, 0)', 'rgb(255, 0, 0)', 'rgb(255, 255, 0)', 'rgb(173, 216, 230)' ],
            borderColor: ['rgb(60, 232, 142)', 'rgb(255, 165, 0)', 'rgb(255, 0, 0)', 'rgb(255, 255, 0)', 'rgb(173, 216, 230)' ],
            data: [12,17,22],
        },
    ]
};

chart = new Chart('chart-4', {
    type: 'bar',
    data: data_chart_4,
    options: {
        scales: {
            xAxes: [{
                stacked: false
            }],
            yAxes: [{
                stacked: false
            }],
        },
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false,
            position: 'bottom',
            boxWidth: 0,
        }
    }
});*/