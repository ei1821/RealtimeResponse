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
