export const getChartTheme = (isDark = true) => ({
    chart: {
        background: 'transparent',
        fontFamily: 'Instrument Sans, sans-serif',
        toolbar: {
            show: false,
        },
    },
    colors: ['#d6a63f', '#a67c2b', '#7d5d21'],
    theme: {
        mode: isDark ? 'dark' : 'light',
        monochrome: {
            enabled: false,
        },
    },
    grid: {
        borderColor: isDark ? '#2a243b' : '#e0e0e0',
        strokeDashArray: 4,
    },
    legend: {
        position: 'bottom',
        horizontalAlign: 'center',
        fontSize: '14px',
        markers: {
            radius: 12,
        },
        itemMargin: {
            horizontal: 10,
            vertical: 5,
        },
    },
    markers: {
        strokeWidth: 4,
        strokeOpacity: 0.8,
        strokeColors: ['#d6a63f'],
    },
    fill: {
        opacity: 0.8
    },
    tooltip: {
        theme: isDark ? 'dark' : 'light',
    },
    responsive: [
        {
            breakpoint: 480,
            options: {
                chart: {
                    width: '100%'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    ]
});
