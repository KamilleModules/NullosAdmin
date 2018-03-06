<?php


use Theme\NullosTheme;

NullosTheme::useLib("stats");
?>
<div class="nullos-dashboard" role="main" id="nullos-dashboard-gui">
    <div class="">


        <div class="row">
            <div class="col-md-12">
                <div class="x_panel" style="display:flex">
                    <div class="btn-group" style="flex: auto" role="group" aria-label="...">
                        <button type="button" class="btn btn-default btn-sm preset preset-day">Jour</button>
                        <button type="button" class="btn btn-default btn-sm preset preset-month">Mois</button>
                        <button type="button" class="btn btn-default btn-sm preset preset-year">Année</button>
                        <button type="button" class="btn btn-default btn-sm preset preset-day-1">Jour-1</button>
                        <button type="button" class="btn btn-default btn-sm preset preset-month-1">Mois-1</button>
                        <button type="button" class="btn btn-default btn-sm preset preset-year-1">Année-1</button>
                    </div>


                    <div class="filter">
                        <div id="reportrange" class="pull-right"
                             style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-line-chart"></i> Aperçu de l'activité
                        </h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="col-md-3 col-sm-12 col-xs-12">


                            <div class="x_content">


                                <div class="row"
                                     style="border-bottom: 1px solid #E0E0E0; padding-bottom: 5px; margin-bottom: 5px;">


                                    <ul class="list-inline widget_tally">
                                        <li>
                                            <p>
                                                <span class="month">
                                                    <a href="#">Visiteurs en ligne</a>
                                                    <br>
                                                    <span class="nul_smaller">Dans les 30 dernières minutes</span>
                                                </span>
                                                <span class="count dash-target"
                                                      data-id="visitorsLast30Min"><?php echo $v['visitorsLast30Min']; ?></span>
                                            </p>
                                        </li>
                                        <li class="no-border-bottom">
                                            <p>
                                                <span class="month">
                                                    <a href="#">Paniers actifs</a>
                                                    <br>
                                                    <span class="nul_smaller">Dans les 30 dernières minutes</span>
                                                </span>
                                                <span class="count dash-target"
                                                      data-id="activeCartsLast30Min"><?php echo $v['activeCartsLast30Min']; ?></span>
                                            </p>
                                        </li>
                                    </ul>
                                    <ul class="list-inline widget_tally">
                                        <li class="no-border-bottom">
                                            <div class="bar_separator">
                                                <i class="fa fa-clock-o"></i>
                                                Actuellement en attente
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <span class="month">
                                                    <a href="#">Commandes</a>
                                                </span>
                                                <span class="count dash-target"
                                                      data-id="nbPreparingOrders"><?php echo $v['nbPreparingOrders']; ?></span>
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                <span class="month">
                                                    <a href="#">Paniers abandonnés</a>
                                                </span>
                                                <span class="count"><?php echo $v['nbAbandonedCarts']; ?></span>
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                <span class="month">
                                                    <a href="#">Produits en rupture de stock</a>
                                                </span>
                                                <span class="count dash-target"
                                                      data-id="nbOutOfStockProducts"><?php echo $v['nbOutOfStockProducts']; ?></span>
                                            </p>
                                        </li>
                                    </ul>
                                    <ul class="list-inline widget_tally">
                                        <li class="no-border-bottom">
                                            <div class="bar_separator">
                                                <i class="fa fa-user"></i> Clients et newsletter
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <span class="month">
                                                    <a href="#">Nouveaux clients</a>
                                                </span>
                                                <span class="count dash-target"
                                                      data-id="nbNewCustomers"><?php echo $v['nbNewCustomers']; ?></span>
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                <span class="month">
                                                    <a href="#">Nouveaux abonnements</a>
                                                </span>
                                                <span class="count dash-target"
                                                      data-id="nbNewNewsletterSubscribers"><?php echo $v['nbNewNewsletterSubscribers']; ?></span>
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                <span class="month">
                                                    <a href="#">Total des abonnés</a>
                                                </span>
                                                <span class="count dash-target"
                                                      data-id="nbTotalNewsletterSubscribers"><?php echo $v['nbTotalNewsletterSubscribers']; ?></span>
                                            </p>
                                        </li>
                                    </ul>
                                </div>


                            </div>

                        </div>


                        <div class="col-md-9 col-sm-12 col-xs-12">

                            <div class="row">
                                <ul class="stats-overview">
                                    <li class="stat-cat active" data-id="revenue">
                                        <span class="name"> <a href="#">Ventes </span>
                                        <span class="value text-success dash-target"
                                              data-id="revenue"> <?php echo $v['revenue']; ?> </span>
                                    </li>
                                    <li class="stat-cat" data-id="nbOrders">
                                        <span class="name"> Commandes </span>
                                        <span class="value text-success dash-target"
                                              data-id="nbOrders"> <?php echo $v['nbOrders']; ?> </span>
                                    </li>
                                    <li class="stat-cat" data-id="avgCart">
                                        <span class="name">Panier moyen</span>
                                        <span class="value text-success dash-target"
                                              data-id="avgCart"> <?php echo $v['avgCart']; ?> </span>
                                    </li>
                                    <li class="stat-cat" data-id="visitors">
                                        <span class="name">Visites</span>
                                        <span class="value text-success dash-target"
                                              data-id="visitors"> <?php echo $v['visitors']; ?> </span>
                                    </li>
                                    <li class="stat-cat" data-id="conversionRate">
                                        <span class="name">Taux de transformation</span>
                                        <span class="value text-success dash-target"
                                              data-id="conversionRate"> <?php echo $v['conversionRate']; ?> </span>
                                    </li>
                                    <li class="stat-cat" data-id="netProfit">
                                        <span class="name">Bénéfice net</span>
                                        <span class="value text-success dash-target"
                                              data-id="netProfit"> <?php echo $v['netProfit']; ?> </span>
                                    </li>
                                </ul>
                                <br/>
                            </div>

                            <div class="demo-container" style="height:280px; position: relative;">
                                <div id="chart_plot_02" class="demo-placeholder">
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<script>
    jqueryComponent.ready(function () {


        //----------------------------------------
        // DASHBOARD GUI
        //----------------------------------------
        /**
         * When you change the date, the gui updates using ajax...
         */
        var jDashboard = $('#nullos-dashboard-gui');
        var api = ekomApi.inst();


        //----------------------------------------
        // DATE RANGE PICKER
        //----------------------------------------
        var curDateStart = null;
        var curDateEnd = null;
        var myFormat = "D MMMM YYYY";
        var dashboardApplyFormat = 'YYYY-MM-DD';
        var presetActiveClass = "btn-primary";
        var statCatActiveClass = "active";
        var dateStart = "<?php echo $v['startDate']; ?>";
        var dateEnd = "<?php echo $v['endDate']; ?>";
        var jPickerTrigger = $('#reportrange');
        var jPickerTriggerSpan = $('#reportrange span');

        var jPlot = $('#chart_plot_02');
        var chartData = [];
        var flotOptions = {
            grid: {
                show: true,
                aboveData: true,
                color: "#3f3f3f",
                labelMargin: 10,
                axisMargin: 0,
                borderWidth: 0,
                borderColor: null,
                minBorderMargin: 5,
                clickable: true,
                hoverable: true,
                autoHighlight: true,
                mouseActiveRadius: 100
            },
            series: {
                lines: {
                    show: true,
                    fill: true,
                    lineWidth: 2,
                    shadowSize: 0,
                    steps: false
                },
                points: {
                    show: true,
                    radius: 4.5,
                    symbol: "circle",
                    lineWidth: 3.0
                }
            },
            legend: {
                position: "ne",
                margin: [0, -25],
                noColumns: 0,
                labelBoxBorderColor: null,
                labelFormatter: function (label, series) {
                    return label + '&nbsp;&nbsp;';
                },
                width: 40,
                height: 1
            },
            colors: ['#96CA59', '#3F97EB', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282'],
            shadowSize: 0,
            tooltip: true,
            tooltipOpts: {
                content: "%s: %y.0",
                xDateFormat: "%d/%m",
                shifts: {
                    x: -30,
                    y: -50
                },
                defaultTheme: false
            },
            yaxis: {
                min: 0,
                zoomRange: false,
                panRange: false,
            },
            xaxis: {
                mode: "time",
                minTickSize: [1, "day"],
                timeformat: "%d/%m/%y"
            },
            zoom: {
                interactive: true
            },
            pan: {
                interactive: true
            }
        };


        var cb = function (start, end, label) {
            jPickerTriggerSpan.html(start.format(myFormat) + ' - ' + end.format(myFormat));
        };

        var optionSet1 = {
            startDate: dateStart,
            endDate: dateEnd,
            // minDate: '01/01/2012',
            // maxDate: '12/31/2015',
            dateLimit: {
                days: 60
            },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            // ranges: {
            //     'Today': [moment(), moment()],
            //     'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            //     'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            //     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //     'This Month': [moment().startOf('month'), moment().endOf('month')],
            //     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            // },
            opens: 'left',
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-small btn-primary',
            cancelClass: 'btn-small',
            format: 'MM/DD/YYYY',
            separator: ' to ',
            locale: {
                // format: 'YYYY-MM-DD',
                applyLabel: 'Appliquer',
                cancelLabel: 'Fermer',
                fromLabel: 'De',
                toLabel: 'à',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                daysOfWeek: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Decembre'],
                firstDay: 1
            }
        };


        jPickerTrigger.daterangepicker(optionSet1, cb);


        function applyNewDateRange(dateStart, dateEnd) {

            curDateStart = dateStart;
            curDateEnd = dateEnd;

            var momentStart = moment(dateStart);
            var momentEnd = moment(dateEnd);

            jPickerTriggerSpan.html(momentStart.format(myFormat) + ' - ' + momentEnd.format(myFormat));
            // $('#reportrange').data('daterangepicker').setStartDate('03/01/2017');
            jPickerTrigger.data('daterangepicker').setStartDate(momentStart.format("DD/MM/YYYY"));
            jPickerTrigger.data('daterangepicker').setEndDate(momentEnd.format("DD/MM/YYYY"));


            var graph = "revenue";
            var jGraphCat = jDashboard.find('.stat-cat.active');
            if (jGraphCat.length) {
                graph = jGraphCat.attr("data-id");
            }


            api.utils.request("Ekom:back.dashboard-gui", {
                graph: graph,
                date_start: momentStart.format("YYYY-MM-DD"),
                date_end: momentEnd.format("YYYY-MM-DD")
            }, function (result) {


                jDashboard.find('.dash-target').each(function () {
                    var key = $(this).attr('data-id');
                    if (key in result) {
                        $(this).html(result[key]);
                    }
                });

                // var chartData = [];
                // for (var i = 0; i < 30; i++) {
                //     chartData.push([new Date(Date.today().add(i).days()).getTime(), randNum() + i + i + 10]);
                // }
                chartData = result['graph'];
                // console.log(chartData);
                // console.log(result['graph']);


                var n = chartData.length;
                flotOptions.xaxis.min = chartData[0][0];
                flotOptions.xaxis.max = chartData[n - 1][0];


                $.plot(jPlot,
                    [{
                        label: "",
                        data: chartData,
                        lines: {
                            fillColor: "rgba(150, 202, 89, 0.12)"
                        },
                        points: {
                            fillColor: "#fff"
                        }
                    }], flotOptions);
            });

        }


        jPickerTrigger.on('apply.daterangepicker', function (ev, picker) {
            jDashboard.find('.preset').removeClass(presetActiveClass);
            applyNewDateRange(picker.startDate.format(dashboardApplyFormat), picker.endDate.format(dashboardApplyFormat));
        });
        // $('#options1').click(function () {
        //     $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
        // });


        //----------------------------------------
        // PLOT INIT
        //----------------------------------------
        if (jPlot.length) {
            var plot = $.plot(jPlot,
                [{
                    label: "",
                    data: chartData,
                    lines: {
                        fillColor: "rgba(150, 202, 89, 0.12)"
                    },
                    points: {
                        fillColor: "#fff"
                    }
                }], flotOptions);

            //----------------------------------------
            // tool tip
            //----------------------------------------
            $("<div id='tooltip'></div>").css({
                position: "absolute",
                display: "none",
                border: "1px solid rgb(194, 204, 255)",
                padding: "2px",
                "background-color": "rgb(225, 232, 254)",
                opacity: 0.80
            }).appendTo("body");

            jPlot.bind("plothover", function (event, pos, item) {

                if (item) {
                    var y = item.datapoint[1];

                    $("#tooltip").html(y)
                        .css({top: item.pageY + 5, left: item.pageX + 5})
                        .fadeIn(200);
                } else {
                    $("#tooltip").hide();
                }
            });
        }


        //----------------------------------------
        // GUI
        //----------------------------------------
        jDashboard.on('click', function (e) {
            var jTarget = $(e.target);
            if (jTarget.hasClass("preset")) {
                var dateStart, dateEnd;
                var format = dashboardApplyFormat;
                if (jTarget.hasClass("preset-day")) {
                    dateStart = moment().startOf("day").format(format);
                    dateEnd = moment().endOf("day").format(format);
                }
                else if (jTarget.hasClass("preset-month")) {
                    dateStart = moment().startOf("month").format(format);
                    dateEnd = moment().endOf("month").format(format);
                }
                else if (jTarget.hasClass("preset-year")) {
                    dateStart = moment().startOf("year").format(format);
                    dateEnd = moment().endOf("year").format(format);
                }
                else if (jTarget.hasClass("preset-day-1")) {
                    dateStart = moment().startOf("day").subtract(1, 'day').format(format);
                    dateEnd = moment().endOf("day").subtract(1, 'day').format(format);
                }
                else if (jTarget.hasClass("preset-month-1")) {
                    dateStart = moment().subtract(1, 'month').startOf("month").format(format);
                    dateEnd = moment().subtract(1, 'month').endOf("month").format(format);
                }
                else if (jTarget.hasClass("preset-year-1")) {
                    dateStart = moment().subtract(1, 'year').startOf("year").format(format);
                    dateEnd = moment().subtract(1, 'year').endOf("year").format(format);
                }

                jDashboard.find('.preset').removeClass(presetActiveClass);
                jTarget.addClass(presetActiveClass);


                applyNewDateRange(dateStart, dateEnd);
                return false;
            }
        });

        jDashboard.on('click.2', ".stat-cat", function (e) {
            var jTarget = $(e.target);
            var jLi = jTarget.closest(".stat-cat");
            jDashboard.find('.stat-cat').removeClass(statCatActiveClass);
            jLi.addClass(statCatActiveClass);
            applyNewDateRange(curDateStart, curDateEnd);

        });


        //----------------------------------------
        // INIT
        //----------------------------------------
        jDashboard.find('.preset-month').trigger("click");

    });
</script>