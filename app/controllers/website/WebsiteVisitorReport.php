<?php
class WebsiteVisitorReport extends BaseController{
	public function webVisitorReport(){
		$visitorYearList = DB::select("select count(month(CreatedOn)) as VisitorCount, year(CreatedOn) as YearVisited FROM webvisitors group by year(CreatedOn) order by YearVisited desc");
		$visitorMonthList = DB::select("select count(*) as VisitorCount, year(CreatedOn) as YearVisited, monthname(str_to_date(month(CreatedOn), '%m')) as MonthName, month(CreatedOn) as MonthVisited FROM webvisitors group by year(CreatedOn), month(CreatedOn) order by MonthVisited desc");

		return View::make('website.webvisitorreport')
					->with('visitorYearList',$visitorYearList)
					->with('visitorMonthList',$visitorMonthList);
	}
}