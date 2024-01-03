var UITree = function () {

    var handleSample1 = function () {
        $('.tree_1').jstree({
            "core" : {
                "themes" : {
                    "responsive": false
                }            
            },
            "types" : {
                "default" : {
                    "icon" : "fa fa-folder icon-state-warning icon-lg"
                },
                "file" : {
                    "icon" : "fa fa-file icon-state-warning icon-lg"
                }
            },
            "plugins": ["types"]
        });

        // handle link clicks in tree nodes(support target="_blank" as well)
        $('.tree_1').on('select_node.jstree', function(e,data) {
            var id = $('#'+data.selected).data('id');
            var currentParent = $('#'+data.selected).parents('tr');
            currentParent.find('.idtoset').val(id);
        });
    }

    return {
        //main function to initiate the module
        init: function () {

            handleSample1();

        }

    };

}();