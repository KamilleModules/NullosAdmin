<?php


namespace Theme\NullosAdmin\Ekom\Back\Category;


use Bat\StringTool;

use Module\Ekom\Helper\EkomLinkHelper;
use Theme\NullosAdmin\FancyTree\FancyTreeRenderer;
use Theme\NullosTheme;

class CategoryFancyTreeRenderer extends FancyTreeRenderer
{


    public static function create()
    {
        return new static();
    }


    public function display()
    {
        $baseUrl = "/service/Ekom/ecp/api?action=";

        $url = $baseUrl . "back.fancy-tree.category";

        NullosTheme::useLib("fancytree");
        $cssId = StringTool::getUniqueCssId();

        $linkCategory = EkomLinkHelper::getShopSectionLink("category", [
            "show_form" => 1,
            "category_id" => "",
        ]);


        ?>
        <style>
            .ui-menu {
                width: 180px;
                font-size: 63%;
            }

            .ui-menu kbd { /* Keyboard shortcuts for ui-contextmenu titles */
                float: right;
            }

            /* custom alignment (set by 'renderColumns'' event) */
            td.alignRight {
                text-align: right;
            }

            td.alignCenter {
                text-align: center;
            }

            td input[type=input] {
                width: 40px;
            }

            .cat-name {
                padding-left: 5px;
            }
        </style>

        <div class="fancy-tree-widget">

            <h1>Catégories</h1>
            <p>
                <label>Filter:</label>
                <input name="search" placeholder="Filter..." autocomplete="off">
                <button id="btnResetSearch">&times;</button>
                <span id="matches"></span>
            </p>

            <table id="<?php echo $cssId; ?>" class="fancy-tree-widget-table">
                <colgroup>
                    <col width="0px">
                    <col width="50px">
                    <col width="350px">
                    <col width="50px">
                    <col width="50px">
                    <col width="50px">
                </colgroup>
                <thead>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>Label</th>
                    <th>Name</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="alignCenter"></td>
                    <td></td>
                    <td></td>
                    <td><span class="cat-name"></span></td>
                    <td><span class="cat-order"></span></td>
                    <td>
                        <a target="_blank" class="edit-link" title="modifier" href="#"><i class="fa fa-edit"></i></a>
                        <a target="_blank" class="delete-link"
                           data-cat="0"
                           title="supprimer" href="#"><i
                                    class="fa fa-times-circle"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>


        <script type="text/javascript">
            jqueryComponent.ready(function () {


                $(function () {


                    var api = ekomApi.inst();
                    var editLinkBase = "<?php echo $linkCategory; ?>";

                    var jTree = $("#<?php echo $cssId; ?>");

                    var oTree = null;
                    jTree.fancytree({
                        checkbox: false,
                        titlesTabbable: true,     // Add all node titles to TAB chain
                        quicksearch: true,        // Jump to nodes when pressing first character
                        // source: SOURCE,
                        source: {url: "<?php echo $url; ?>"},
                        extensions: [
                            "edit",
                            "dnd",
                            "table",
                            "filter",
                            "persist",
                            "gridnav"
                        ],
                        persist: {
                            // Available options with their default:
                            cookieDelimiter: "~",    // character used to join key strings
                            cookiePrefix: undefined, // 'fancytree-<treeId>-' by default
                            cookie: { // settings passed to jquery.cookie plugin
                                raw: false,
                                expires: "",
                                path: "",
                                domain: "",
                                secure: false
                            },
                            expandLazy: false, // true: recursively expand and load lazy nodes
                            expandOpts: undefined, // optional `opts` argument passed to setExpanded()
                            overrideSource: true,  // true: cookie takes precedence over `source` data attributes.
                            store: "auto",     // 'cookie': use cookie, 'local': use localStore, 'session': use sessionStore
                            types: "active expanded focus selected"  // which status types to store
                        },
                        dnd: {
                            preventVoidMoves: true,
                            preventRecursiveMoves: true,
                            autoExpandMS: 400,
                            dragStart: function (node, data) {
                                return true;
                            },
                            dragEnter: function (node, data) {
                                // return ["before", "after"];
                                return true;
                            },
                            dragDrop: function (node, data) {

                                var sourceId = data.otherNode.data.category_id;
                                var targetId = node.data.category_id;
                                var mode = data.hitMode;

                                api.utils.request("back.move-category", {
                                    source_id: sourceId,
                                    target_id: targetId,
                                    mode: mode
                                }, function (r) {
                                    if (true === r.result) {
                                        data.otherNode.moveTo(node, data.hitMode);
                                        data.otherNode.parent.render(true);
                                        node.parent.render(true);
                                        tree = $.ui.fancytree.getTree();
                                        tree.reload();
                                    }
                                });

                            }
                        },
                        edit: {
                            // triggerStart: ["f2", "shift+click", "mac+enter"],
                            // close: function (event, data) {
                            //     if (data.save && data.isNew) {
                            //         // Quick-enter: add new nodes until we hit [enter] on an empty title
                            //         $("#tree").trigger("nodeCommand", {cmd: "addSibling"});
                            //     }
                            // }
                        },
                        table: {
                            indentation: 20,
                            nodeColumnIdx: 2,
                            checkboxColumnIdx: null
                        },
                        gridnav: {
                            autofocusInput: false,
                            handleCursorKeys: true
                        },
                        filter: {
                            autoApply: true,   // Re-apply last filter if lazy data is loaded
                            autoExpand: false, // Expand all branches that contain matches while filtered
                            counter: true,     // Show a badge with number of matching child nodes near parent icons
                            fuzzy: false,      // Match single characters in order, e.g. 'fb' will match 'FooBar'
                            hideExpandedCounter: true,  // Hide counter badge if parent is expanded
                            hideExpanders: false,       // Hide expanders if all child nodes are hidden by filter
                            highlight: true,   // Highlight matches by wrapping inside <mark> tags
                            leavesOnly: false, // Match end nodes only
                            nodata: true,      // Display a 'no data' status node if result is empty
                            mode: "hide"
                        },

                        lazyLoad: function (event, data) {
                            data.result = {url: "<?php echo $url; ?>"};
                        },
                        createNode: function (event, data) {
                            var node = data.node,
                                $tdList = $(node.tr).find(">td");

                            // Span the remaining columns if it's a folder.
                            // We can do this in createNode instead of renderColumns, because
                            // the `isFolder` status is unlikely to change later
                            // if (node.isFolder()) {
                            //     $tdList.eq(2)
                            //         .prop("colspan", 6)
                            //         .nextAll().remove();
                            // }
                        },
                        renderColumns: function (event, data) {


                            var node = data.node,
                                $tdList = $(node.tr).find(">td");


                            var editLink = editLinkBase + node.data.category_id;

                            $tdList.eq(1).text(node.data.category_id);
                            $tdList.eq(3).find(".cat-name").html(node.data.name);
                            $tdList.eq(4).find(".cat-order").html(node.data.order);
                            var jActionTd = $tdList.eq(5);
                            jActionTd.find(".edit-link").attr('href', editLink);
                            jActionTd.find(".delete-link").attr('data-cat', node.data.category_id);


                        }
                    });


                    $("input[name=search]").keyup(function (e) {
                        var n,
                            tree = $.ui.fancytree.getTree(),
                            opts = {},
                            filterFunc = $("#branchMode").is(":checked") ? tree.filterBranches : tree.filterNodes,
                            match = $(this).val();

                        opts.mode = "hide";

                        if (e && e.which === $.ui.keyCode.ESCAPE || $.trim(match) === "") {
                            $("button#btnResetSearch").click();
                            return;
                        }
                        if ($("#regex").is(":checked")) {
                            // Pass function to perform match
                            n = filterFunc.call(tree, function (node) {
                                return new RegExp(match, "i").test(node.title);
                            }, opts);
                        } else {
                            // Pass a string to perform case insensitive matching
                            n = filterFunc.call(tree, match, opts);
                        }
                        $("button#btnResetSearch").attr("disabled", false);
                        $("span#matches").text("(" + n + " matches)");
                    }).focus();

                    $("button#btnResetSearch").click(function (e) {
                        tree = $.ui.fancytree.getTree(); // ling added that
                        $("input[name=search]").val("");
                        $("span#matches").text("");
                        tree.clearFilter();
                    }).attr("disabled", true);


                    jTree.on('click', ".delete-link", function (event) {

                        if (window.confirm("Are you sure?")) {

                            var catId = $(this).attr('data-cat');
                            var node = $.ui.fancytree.getNode(event);

                            node.remove();

                            api.utils.request("back.delete-category", {
                                category_id: catId
                            }, function (r) {
                                if (true === r.result) {
                                    node.remove();
                                }
                            });
                        }

                        return false;
                    });

                    oTree = $.ui.fancytree.getTree();

                });
            });
        </script>
        <?php
    }
}