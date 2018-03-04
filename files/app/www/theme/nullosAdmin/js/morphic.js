/**
 * Depends on jquery.
 * Required css-classes:
 *
 *
 * - (your container)
 * ----- morphic-table
 *
 *
 *
 */
if ('undefined' === typeof window.Morphic) {


    window.Morphic = function (options) {
        var o = $.extend({
            /**
             * a jquery element containing the table.
             * The table must have the class: morphic-table
             */
            element: null,
            onRowToggle: function (focusedRow, selectedRows) {

            },
            onMorphicAction: function (name) {

            }
        }, options);


        var zis = this;
        var jElement = o.element;


        function getTable() {
            var jTable = jElement.find('.morphic-table:first');
            return jTable;
        }

        function findNextDir(sortDir) {
            if ('asc' !== sortDir && 'desc' !== sortDir) {
                sortDir = null;
            }
            var nextDir = "null";
            if (null === sortDir) {
                nextDir = 'asc';
            }
            else if ("asc" === sortDir) {
                nextDir = 'desc';
            }
            else {
                nextDir = 'null';
            }
            return nextDir;
        }


        function getRowValues(jTarget) {
            var jRow = jTarget.closest("tr");
            var ret = {};
            jRow.find('> td').each(function () {
                var col = $(this).attr("data-column");
                if ('undefined' !== typeof col && '_action' !== col) {
                    var value = $(this).attr("data-value");
                    ret[col] = value;
                }
            });
            return ret;
        }

        function makeTdClickable() {

            /**
             * When we click on any row, it triggers the update link (makes the gui more intuitive)
             */
            getTable()
                .off('click.morphic')
                .on('click.morphic', 'td', function (e) {
                    var jTarget = $(e.target);

                    /**
                     * We apply this behaviour only if we are not on a special thing like:
                     * - checkbox
                     * - link
                     * - ...
                     *
                     * (otherwise, the user would be confused...)
                     */
                    if (jTarget.is('td')) {
                        var jLink = $(this).closest('tr').find('a[data-name=update]');
                        if (jLink.length > 0) {
                            window.location.href = jLink.attr('href');
                        }
                    }
                });
        }


        var stt = 1;

        jElement.on('click', '.morphic', function (e) {
            var jTarget = $(e.currentTarget);
            if (jTarget.hasClass("morphic")) {
                if (jTarget.hasClass("morphic-table-sort")) {


                    stt++;
                    var curDir = jTarget.attr("data-sort-dir");
                    var nextDir = findNextDir(curDir);
                    jTarget.attr("data-sort-dir", nextDir);
                    zis.refresh();
                    return false;
                }
                else if (jTarget.hasClass("morphic-table-search-btn")) {
                    zis.refresh();
                    return false;
                }
                else if (jTarget.hasClass("morphic-table-search-reset-btn")) {
                    var jTable = getTable();
                    jTable.find('.morphic-table-filter').val("");
                    zis.refresh();
                    return false;
                }
                else if (jTarget.hasClass("morphic-page")) {
                    var page = jTarget.attr('data-page');
                    var jTable = getTable();
                    jTable.attr('data-page', page);
                    zis.refresh();
                    return false;
                }
                else if (jTarget.hasClass("morphic-nipp")) {
                    var nipp = jTarget.attr('data-nipp');
                    var jTable = getTable();
                    jTable.attr('data-nipp', nipp);
                    zis.refresh();
                    return false;
                }
                else if (jTarget.hasClass("morphic-checkbox")) {
                    var selectedRows = zis.getSelectedRows();
                    var focusedRow = zis.getRowValuesByTarget(jTarget);
                    /**
                     * handling actions
                     */
                    var hasRows = (selectedRows.length > 0);
                    jElement.find(".morphic-action").each(function () {
                        if ($(this).hasClass("will-enable")) {
                            if (true === hasRows) {
                                $(this).removeClass("disabled");
                            }
                            else {
                                $(this).addClass("disabled");
                            }
                        }
                    });


                    /**
                     * User callback
                     */
                    o.onRowToggle(focusedRow, selectedRows);


                }
                //----------------------------------------
                // ACTIONS (list actions and row actions)
                //----------------------------------------
                else if (
                    jTarget.hasClass("morphic-action") ||
                    jTarget.hasClass("morphic-row-action")
                ) {

                    var isRowAction = jTarget.hasClass("morphic-row-action");


                    if (false === jTarget.hasClass("disabled")) {

                        var name = jTarget.attr("data-name");
                        //----------------------------------------
                        // PREBUILT ACTIONS
                        //----------------------------------------
                        var confirmText = jTarget.attr("data-confirm");
                        var confirmTitle = jTarget.attr("data-confirm-title") || null;
                        var confirmButtonCancelText = jTarget.attr("data-confirm-cancel-text") || null;
                        var confirmButtonOkText = jTarget.attr("data-confirm-ok-text") || null;


                        var func = function () {
                            if ('delete' === name) {
                                var rows = {};
                                if (false === isRowAction) {
                                    rows = zis.getSelectedRows();
                                }
                                else {
                                    rows = [
                                        zis.getRowValuesByTarget(jTarget)
                                    ];
                                }
                                zis.refresh("delete", {
                                    rows: rows
                                }, function () {
                                    window.Morphic.onDeleteAfter();
                                });
                            }
                            //----------------------------------------
                            // USER DEFINED ACTIONS
                            //----------------------------------------
                            else {
                                o.onMorphicAction(name);
                            }
                        };


                        if ('undefined' !== typeof confirmText) {
                            nullosApi.inst().confirm(confirmText, function () {
                                func();
                            }, confirmTitle, confirmButtonOkText, confirmButtonCancelText);
                        }
                        else {
                            func();
                        }
                    }

                    // return false;

                }
            }
        });

        jElement.on('keydown', '.morphic-table-filter', function (e) {
            if (e.which == 13) {
                zis.refresh();
            }
        });

        makeTdClickable();

        /**
         * This method refreshes the view.
         * Use it when the user clicks a sort trigger, or launches a search,
         * or when a row has been deleted, ...
         */
        this.refresh = function (actionType, params, onRefreshAfter) {


            actionType = actionType || 'fetch';
            var jTable = getTable();
            var viewId = jTable.attr('data-view-id');
            var serviceUri = jTable.attr('data-service-uri');
            var page = jTable.attr('data-page') || 1;
            var nipp = jTable.attr('data-nipp') || 20;
            var context = window.Morphic.context;
            if (null === context) {
                var jContext = jElement.find('.morphic-context');
                context = jContext.data();
            }


            // collect sorts
            var sortDirs = {};
            jTable.find('.morphic-table-sort').each(function () {
                var sortDir = $(this).attr("data-sort-dir");
                var column = $(this).attr("data-column");
                sortDirs[column] = sortDir;
            });


            // collect filters
            var filters = {};
            jTable.find('.morphic-table-filter').each(function () {
                var value = $(this).val();
                var column = $(this).attr("data-column");
                filters[column] = value;
            });

            // fetch/inject data
            var data = {
                actionType: actionType,
                viewId: viewId,
                sort: sortDirs,
                filters: filters,
                page: page,
                nipp: nipp,
                context: context,
                params: params
            };


            // $.post(url, data, function (response) {
            //     jElement.empty().append(response.view);
            // }, "json");

            nullosApi.inst().request("Ekom:back.morphic", data, function (response) {
                jElement.empty().append(response.view);
                onRefreshAfter && onRefreshAfter();
                makeTdClickable();
            });


        };


        this.getSelectedRows = function () {
            var jTable = getTable();
            var ret = [];
            jTable.find('.morphic-checkbox').each(function () {
                var isChecked = $(this).prop('checked');
                if (true === isChecked) {
                    var values = getRowValues($(this));
                    ret.push(values);
                }
            });
            return ret;
        };

        this.getRowValuesByTarget = function (jTarget) {
            return getRowValues(jTarget);
        };
    };


    //----------------------------------------
    // HOOKS (just for the current page)
    //----------------------------------------
    /**
     * override the function below
     */
    window.Morphic.onDeleteAfter = function () {
    };
    window.Morphic.context = null;
}