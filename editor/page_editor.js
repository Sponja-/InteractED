var categories;

var nextID = 0;
var $selectedElement = $("#content");
var dragging = false;

$(document).ready(function() {
    $("#content").css({
        "height" : ($(window).height() - $("#side-nav").height()) + "px"
    });
    $("#side-nav-button").sideNav();
    $(".collapsible").collapsible();
    $("#page-edit-name").val(pageName);
    $("#page-edit-category").val(pageCategory);
    category.loadTree();
    categories = category.getCategories();
    var category_image = {};
    /*
    NOT IMPLEMENTED
    for(var cat in categories)
        category_image[cat] = "../category/images/" + cat + ".jpg";
    $("#page-edit-category").autocomplete({
        data: category_image,
        limit: 10,
        minLength: 1
    });
    */
    initDialogs();
});

$(window).on("resize", function() {
    $("#content").css("height", ($(window).height() - $("#side-nav").height()) + "px");
});

function initDialogs() {

    $("#edit-page-dialog").modal({
        dismissible: true,
        endingTop: "50%"
    });

    // Image Dialog

    $("#image-create-dialog").modal({
        dismissible: true,
        endingTop: '50%',
        complete: function() {
            emptyParameters("#image-create-dialog");
            invalidateImage();
        } 
    });

    $("#image-create-button").click(function(){
        createImage();
    });

    // Text Dialog

}

function updatePreview() {
    var $url = $("#image-create-src");
    var $preview = $("#image-create-preview > img");

    var tmpImg = new Image();
    tmpImg.src = $url.val();

    $(tmpImg).one('load', function() {
        if (tmpImg.width == 0 || tmpImg.height == 0)
            invalidateImage();
        else {
            $url.removeClass("invalid").addClass("valid");
            $preview.attr("src", $url.val());

            $("#image-create-width").html(tmpImg.width);
            $("#image-create-height").html(tmpImg.height);

            if(tmpImg.width > tmpImg.height)
                $preview.addClass("adjust-width").removeClass("adjust-height");
            else
                $preview.addClass("adjust-height").removeClass("adjust-width");
        }
    });

    $(tmpImg).one("error", function() {
        invalidateImage();
    });
}

function invalidateImage() {
    $url = $("#image-create-src");

    if($url.val() == "")
        $url.removeClass("invalid").removeClass("valid");
    else
        $url.removeClass("valid").addClass("invalid");

    $("#image-create-width, #image-create-height").html("200");
    $("#image-create-preview > img").attr("src", "no_image_selected.gif");
}

function openCreateDialog(type) {
    console.log("opening: #" + type + "-create-dialog");
    $("#" + type + "-create-dialog").modal("open");
    $(".sideNav-button").sideNav("hide");
}

function emptyParameters(id) {
    $(id + " .input").each(function(){
        $(this).val('').blur().removeClass("valid invalid");
    });
}

function createImage() {
    var attributes = {
        "width": $("#image-create-width").html() + "px",
        "height": $("#image-create-height").html() + "px",
        "src": $("#image-create-src").val(),
        "data-type": "image"
    };
    console.log(attributes);
    emptyParameters("#image-create-dialog");
    createWrapper($("<img />").attr(attributes).addClass("inner"));
}

function createWrapper($inner) {
    var $newElement = $("<div></div>").html($inner);
    $newElement.draggable({
        snap: true,
        scroll: false,
        containment: "#content"
    });
    $newElement.children(".handle").hide();
    $newElement.attr({
        "id": "object-" + nextID,
        "data-type": $inner.data("type")
    });
    $newElement.css({
        "width": $inner.attr("width") + "px",
        "height": $inner.attr("height") + "px",
        "float": "left",
        "position": "absolute !important"
    });
    $newElement.append($("<div id='handle-" + nextID + "' class='handle ui-resizable-handle ui-resizable-se'></div>")).resizable({
        handles: {
            "se": "#handle-" + nextID
        },
        aspectRatio: $newElement.width() / $newElement.height()
    });
    $newElement.addClass("object " + $inner.data("type"));
    $newElement.children().css({
        "width": "100%",
        "height": "100%"
    });
    $newElement.on({
        "click" : function(e) {
            selectElement($(this));
            e.stopPropagation();
        },
        "mousedown" : function(e) {
            if(!dragging) {
                selectElement($(this));
                dragging = true;
                e.stopPropagation();
            }
        },
        "mouseup" : function(e) {
            dragging = false;
        }
    });
    $newElement.appendTo($("#content"));
    nextID++;
}

function selectElement($element) {
    unselectElement();
    console.log("selecting element");
    if($selectedElement.attr("id") != $element.attr("id")) {
        $selectedElement = $element;
        $selectedElement.addClass("selected");
        $selectedElement.children(".handle").show();
    }
}

function unselectElement() {
    console.log("unselecting");
    if($selectedElement[0].id != "#content") {
        $selectedElement.removeClass("selected");
        $selectedElement.children(".handle").hide();
    }
    $selectedElement = $("#content");
}

function editButtonClick() {
    if($selectedElement[0].id != "content")
        openEditDialog($selectedElement.data("type"));
    else
        openEditPageDialog();
}

function openEditDialog(type) {
    console.log("Editing " + type + ": " + $selectedElement.attr("id"));
}

function openEditPageDialog() {
    console.log("Editing page");
    $("#edit-page-dialog").modal("open");
}

function savePage() {
    console.log("Saving...");
    var $content = $("<div id=content></div>");
    var maxHeight = 0;
    $("#content").children(".object").each(function() {
        var type = $(this).data("type");
        var $inner = $(this).children(".inner");
        var $elem;
        switch($inner.data("type")) {
            case "image":
                $elem = $("<img />").attr({
                    "src": $inner.attr("src")
                });
            case "text":
                $elem = $("<div></div>");
        }
        $elem.css({
            "position": "relative",
            "left": $(this).css("left"),
            "top": $(this).css("top")
        });
        $elem.width($inner.width());
        $elem.height($inner.height());
        var bottomPos = $(this).position().top + $(this).outerHeight(true);
        if(bottomPos > maxHeight)
        	maxHeight = bottomPos;
        $content.append($elem);
    });

    $content.css({
    	"height": (maxHeight + 50) + "px",
    	"width": $("#content").width() + "px"
    });
    console.log($content[0].outerHTML);

    $.ajax({
        url: "save_page.php",
        type: "POST",
        data: {
            content: $content[0].outerHTML,
            transcript: "",
            name: pageName,
            category: pageCategory
        },
        success: function() {
            console.log("saved");
        },
        error: function() {
            console.log("saving error");
        }
    });
}