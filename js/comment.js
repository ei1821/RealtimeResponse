const top_per = 40;
const mul_per = 6;
function niconicomment(i, txt, is_good) {
  var comment = $("<p>").appendTo("article");

  comment.attr("class", "slideR")
    .attr("class", (is_good ? "goodComment" : "badComment") + " slideR")
    .css("top", (top_per + mul_per * i) + "%")
  var width = comment.text(txt).get(0).offsetWidth;
   comment
    .animate({'marginRight':width + 'px'}, 500)
    .delay(5000)
    .animate({"marginRight": - width - 100 + "px"}, 500)

}
