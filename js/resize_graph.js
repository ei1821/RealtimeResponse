
$(window).on('load resize', function() {
	width = document.body.clientWidth * 0.8;
	//height= document.body.clientHeight * 0.35;

	axis_setting();
	svg.attr("width", width)
	/*.attr("height", height * 2)*/;

	svg.selectAll(".goodrect")
		.attr("width", xScale.bandwidth());
	svg.selectAll(".badrect")
		.attr("width", xScale.bandwidth());
	svg.selectAll(".overrect")
		.attr("width", xScale.bandwidth());

});
