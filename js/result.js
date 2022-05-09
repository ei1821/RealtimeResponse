var N = 50; // 一度に表示するバーの数
var width = 800; // 横幅
var height = 200;
var padding = 30;
const MAGNIFICATION = 2; // 一度の拡大による倍率の変化度

var svg = d3.select("svg");
svg.attr("width", width).attr("height", height*2);

var first = dataset[0], last = dataset.slice(-1)[0];
first = new Date(first.datetime);
last   = new Date(last.datetime);
var data_len = idv(last.getTime() - first.getTime(), 1000);
var max_domain = d3.max(dataset, function(d) { return d.good + d.bad;});
const n = () => min(N, data_len);

/* setup 引数: なし
 * 全体に対するデータセットを生成する
    */
function setup() {
	return data_selecting(data_len, first.getTime());
}
/*
	DATETIMEが[left, right)内のコメントのリストを返す
*/
function comments_range(left, right) {
	var l_idx = binary_search(comments, left, (a, b) => new Date(a.datetime).getTime() <  b);
	var r_idx = binary_search(comments, right,(a, b) => new Date(a.datetime).getTime() <= b);
	return deep_copy(comments.slice(l_idx, r_idx));
}

/*
 * 現在のstepと表示されているデータの時間範囲を秒単位で返す
 */
function datarange_sec() {
	var new_data_len = idv(data_len, MAGNIFICATION ** rate);
	if(new_data_len < n()) {
		if(rate != 0) {
			rate--;
			return datarange_sec();
		}
		new_data_len = n();
	}
	return new_data_len;
}

const step_dataset = () => idv(datarange_sec(), n());

/*
	引数: 	datetime - 基準となるバーのdatetime形式の時間
			rate - 拡大倍率の状態 全体に対してrate ** MAGNIFACATIONだけズームされる
	返り値:	新たなdateset
*/
function zoom_graph(datetime) {
	if(rate < 0) {
		rate = 0;
		return zoom_graph(datetime);
	}
	var new_data_len = datarange_sec();
	var ds = Array();

	var base_utime = new Date(datetime).getTime();
	var new_first = base_utime - idv(new_data_len, 2) * 1000,
		new_last  = base_utime + idv(new_data_len, 2) * 1000;

	if(new_first < first.getTime()) { // originalのデータセットの最古値未満 {
		new_last += first.getTime() - new_first;
		new_first = first.getTime();
	}
	if(last.getTime() < new_last) {
		new_first -= new_last - last.getTime();
		new_last   = last.getTime();
	}

	return data_selecting(new_data_len, new_first);
}

/* data_selecting
 * [first, last] の範囲で均等な間隔でデータを抽出しデータセットを生成する
 * 引数: 範囲の下限firstと上限last unixtime形式
 * 返り値: データセット
 */
function data_selecting(len, scope_first) {
	var ds = Array();
	var step = idv(len, n());

	for(let i = 1; i <= n(); ++i) {
        let target = i * step + len % n(); // i本目のバーのdatetime
        let target_utime = scope_first + target * 1000;
        var idx = binary_search(dataset, target_utime, function(a, b) { return new Date(a.datetime).getTime() < b; });
        if(idx > 0) idx--;
        var tmp = deep_copy(dataset[idx]);
        tmp.datetime = getDateTime(target_utime);
        ds.push(tmp);
	}
	return ds;
}
/*
 * lr=0: 左移動(過去に) lr=1: 右移動(未来に)
 */
function move_graph(old_ds, lr=0) {
	var range_first = new Date(old_ds[0].datetime), range_last = new Date(old_ds.slice(-1)[0].datetime);
	var step_msec = step_dataset() * 1000;
	var target_utime;
	var ds = deep_copy(old_ds);


	if(lr == 0) { // i == 0 だった場合の処理を忘れない
		target_utime = range_first.getTime() - step_msec;
		if(target_utime <= first.getTime())
			return old_ds;
	}
	else {
		target_utime = range_last.getTime() + step_msec;
		if(last.getTime() < target_utime)
			return old_ds;
	}
	var idx = binary_search(dataset, target_utime, (a, b) => new Date(a.datetime).getTime() <= b);
	//targetより大きい最初のidxが返されるため-1
	var new_data = deep_copy(dataset[idx-1]);
	new_data.datetime = getDateTime(target_utime);
	console.log(new_data.datetime);
	if(lr == 0) {
		ds.pop();
		ds.unshift(new_data);
	}
	else {
		ds.shift();
		ds.push(new_data);
	}

	return ds;
}

/* 引数: 表示するバー情報のリスト (dataset.length <= N) */
function make_graph(dataset) {
  // 3. 軸スケールの設定
    var xScale = d3.scaleBand()
        .rangeRound([padding, width - padding])
        .padding(0.1)
        .domain(dataset.map(function(d) { return d.datetime; }));

    var pyScale = d3.scaleLinear()
        .domain([0, max_domain])
        .range([height - padding, padding]);

    var myScale = d3.scaleLinear()
        .domain([0, max_domain])
        .range([height - padding, height * 2 - padding * 3]);

  // 3.5 不要な軸やグラフの削除
    svg.selectAll(".svg-axis").remove();
    svg.selectAll(".goodrect").remove();
    svg.selectAll(".badrect").remove();
    svg.selectAll(".overrect").remove();
    svg.selectAll("svg g").remove();

    // 4. 軸の表示
    svg.append("g")
        .attr("class", "svg-axis")
        .attr("transform", "translate(" + 0 + "," + (height - padding) + ")")
        .call(d3.axisBottom(xScale).tickFormat(function(d, i) {
            return "";
        }).tickSize([0, 0]));

    svg.append("g")
        .attr("class", "svg-axis")
        .attr("transform", "translate(" + 0 + "," + (height * 2 - padding * 2) + ")")
        .call(d3.axisTop(xScale).tickValues(xScale.domain().filter(function(d, i) {
            return !((i + 1)%10) || !i;
        })).tickFormat(function(d, i) {
            return d.slice(-8);
        }));

    svg.append("g")
        .attr("class", "svg-axis")
        .attr("transform", "translate(" + padding + "," + 0 + ")")
        .call(d3.axisLeft(pyScale).tickFormat(function(d, i) {
        if(d == parseInt(d)) return d;
            return "";
        }).ticks(5));

    svg.append("g")
        .attr("class", "svg-axis")
        .attr("transform","translate(" + padding + "," + 0 + ")")
        .call(d3.axisLeft(myScale).tickFormat(function(d, i) {
        if(d == parseInt(d)) return d;
            return "";
        }).ticks(5));

    // 上バーの表示
    svg.append("g")
        .selectAll("rect")
        .data(dataset)
        .enter()
        .append("rect")
        .attr("class", "goodrect")
        .attr("x", d => xScale(d.datetime))
        .attr("y", d => pyScale(d.good))
        .attr("width", xScale.bandwidth())
        .attr("height", d => height - padding - pyScale(d.good))
        .attr("fill", "steelblue");

    // 下バー表示
    svg.append("g")
        .selectAll("rect")
        .data(dataset)
        .enter()
        .append("rect")
        .attr("class", "badrect")
        .attr("x", d => xScale(d.datetime))
        .attr("y", d => height - padding)
        .attr("width", xScale.bandwidth())
        .attr("height", d => myScale(d.bad) + padding - height)
        .attr("fill", "#C20000");

    // マウスオーバー用透明のバー
    svg.append("g")
        .selectAll("rect")
        .data(dataset)
        .enter()
        .append("rect")
        .attr("class", "overrect")
        .attr("x", d => xScale(d.datetime))
        .attr("y", d => pyScale(d.good))
        .attr("data-good", d => d.good)
        .attr("data-bad",  d => d.bad)
        .attr("data-time", d => d.datetime)
        .attr("width", xScale.bandwidth())
        .attr("height", d => height - padding - pyScale(d.good + d.bad))
        .attr("fill-opacity", 0);

}

const min = (a, b) => {
	if(a > b) a = b;
	return a;
}
const max = (a, b) => {
	if(a < b) a = b;
	return a;
}

const binary_search = (arr, val, func, first = 0, last = arr.length) => {
    first -= 1;
    for (let m; last - first > 1;) {
        if (func(arr[m = first + (0 | (last - first) / 2)] , val))
            first = m;
        else
            last = m;
    }
    return last;
}

/* 多分使わないけどlbとubの違いがわからなくなったときに困る */
const upper_bound = (arr, val, first = 0, last = arr.length) => {
    first -= 1;
    while (last - first > 1) {
        const mid = first - Math.floor((last - first) / 2);
        if (arr[mid] <= val) // ココを直すだけ
            first = mid;
        else
            last = mid;
    }
    return last;
}

