const top_per = 40;
const mul_per = 8;
const comment_width = 300;
function niconicomment(i, txt, is_good) {
	var comment = $("<p>").appendTo("#comment_area")
    .attr("class", (is_good ? "goodComment" : "badComment") + " slideR")
	.text(txt)
	.css("top", (top_per + mul_per * i) + "%")
	.delay(50 * i)
    .animate({'marginRight': 0}, 500)
    .delay(5000)
    .animate({"marginRight": - comment_width - 100 + "px"}, 500);

}

function add_comment_history(comment) {
	var text = comment.comment;
	var datetime = comment.datetime;
	var is_good = comment.is_good;
	console.log(text + " " +  datetime +  " " + is_good);

	var com_row = $("<div>").prependTo("#comment_history");
	com_row.addClass("grid_history_row");
	$("<div>").appendTo($(com_row)).addClass("history_row_text").text(text);
	$("<div>").appendTo($(com_row)).addClass("history_row_datetime").text(datetime);

	if(is_good == "1")
		com_row.css("background", "#8FADCC");
	else
		com_row.css("background", "#CC8F8F");

}





