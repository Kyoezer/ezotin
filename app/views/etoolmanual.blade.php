<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>e-Tools | Documentation</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{asset('etoolmanualassets/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('etoolmanualassets/dist/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('etoolmanualassets/dist/css/skins/_all-skins.min.css')}}">
    <link rel="stylesheet" href="{{asset('etoolmanualassets/documentation/style.css')}}">
  </head>
  <body class="skin-blue fixed" data-spy="scroll" data-target="#scrollspy">
    <div class="wrapper">

      <header class="main-header">

        <a href="" class="logo">
          <span class="logo-mini">C D B</span>
		  C D B
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <div class="sidebar" id="scrollspy">

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="nav sidebar-menu">
            <li class="header">TABLE OF CONTENTS</li>
            <li class="active"><a href="#introduction"><i class="fa fa-circle-o"></i> Introduction</a></li>
            <li><a href="#acessing"><i class="fa fa-circle-o"></i> Accessing e-Tool</a></li>
            <li><a href="#login"><i class="fa fa-circle-o"></i> E-Tool Login Page</a></li>
            <li><a href="#home"><i class="fa fa-circle-o"></i> E-Tool Home Page</a></li>
			<li><a href="#upload"><i class="fa fa-circle-o"></i> Upload Tender</a></li>
            <li><a href="#list"><i class="fa fa-circle-o"></i> List of Uploaded Tender</a></li>
			<li>
              <a href="#criteria"><i class="fa fa-circle-o"></i> Set Criteria</a>
				  <ul class="nav treeview-menu">
					<li ><a href="#human">Human Resource</a></li>
					<li><a href="#equipment">Equipment</a></li>
				  </ul>
            </li>
            <li><a href="#evaluation"><i class="fa fa-circle-o"></i> Evaluation</a>
				<ul class="nav treeview-menu">
					<li ><a href="#committee">Evaluation Committee</a></li>
					<li><a href="#awarding">Awarding Committee</a></li>
					<li><a href="#details">Details</a>
						<ul class="nav treeview-menu">
							<li><a href="#contractor">Add Contractor</a></li>
							<li><a href="#result">Process Result</a></li>
						</ul>
					</li>
				  </ul>
			</li>
			<li><a href="#completion"><i class="fa fa-circle-o"></i> Completion</a></li>
          </ul>
        </div>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <h1>
           C D B
          </h1>
          <ol class="breadcrumb">
            <li class="active">Documentation</li>
          </ol>
        </div>

        <!-- Main content -->
        <div class="content body">

<section id="introduction">
  <h2 class="page-header"><a href="#introduction">Introduction</a></h2>
  <p class="lead">
	E-tool is a web based online tender evaluation tool integrated with CRPS and CiNET. E-tool is developed to facilitate and make the work of evaluator easier and less time consuming. The procuring agencies (RGOB funded) are parties or end users of this tool. Each identified focal person nominated by the Procuring Agencies are provided with a login Name and Password which can be used to access into the e-Tool. <br/><br/>

The focal person nominated by the agency/department shall be responsible for feeding in information on e-tool on timely basis as and when required. 
The focal person is expected to use the system judiciously and honestly as instructed/mandated by the tender committee of the agency. <br/><br/>

In case of misuse or fiddling of information on e-tool the focal person will be held accountable. Official concern should surrender the password whenever transferred from the current organization or in case of resignation. In order to prevent miss use of your user account you are recommend to change your password frequently.<br/><br/>
The e-tool has different stages or modules as per the tendering processes which are: 1. Upload Tender, 2. Set Criteria, 3. Evaluation of Bids, 4.Completion report and finally 5. Reports. These five modules will be explained step by step accordingly. One of the main responsibilities of the focal person is to feed the Information correctly and on timely basis otherwise the whole purpose of the e-tool will be defeated.

  <p>

</section><!-- /#introduction -->


<!-- ============================================================= -->

<section id="acessing">

  <h2 class="page-header"><a href="#acessing">Accessing e-Tool</a></h2>
  <p class="lead">
    In order for the procuring agency to upload a tender, the procuring agency first needs to open the CDB website by typing on the internet explorer or any browser the URL: <a href="http://www.cdb.gov.bt">www.cdb.gov.bt.</a>
  </p>
  <div class="row">
   <img src="{{asset('etoolmanualassets/images/start.png')}}"/>
  </div><!-- /.row -->
<p>Here in the website a link called E-Zhotin will be given. It will direct you to the e-tool login page.</p>
</section>
<section id="login">
  <h2 class="page-header"><a href="#login">E-Tool Login Page</a></h2>
  <p><b>System administration module is for administrating the application like:</b></p>
  <p>
	<img src="{{asset('etoolmanualassets/images/login.png')}}"/>
  </p>
  <p>Here you can type in the correct user name and password to gain access to the e-Tool home page. Once the user name and password provided to you by the CDB is entered in the right field just click on the “Login” button to proceed. We recommend you to change your password frequently and not to share with others. <br/>
In case of the focal person is transferred or resigned from the office, the concerned official and the agency should inform CDB to deactivate the user to prevent it from unlawful practices.
</p>
</section>

<section id="home">

  <h4 class="page-header"><a href="">E-Tool Home Page</a></h4>
  <p>After logging in the following page will be displayed.</p>
 
 <p>
	<img src="{{asset('etoolmanualassets/images/home.png')}}"/>
  </p>

  <p class="lead">
   This homepage displays the latest Notice and News for Procuring Agency and also the menu for various interfaces is shown at the top menu of the page.
  </p>
 
</section>


<section id="upload">
  <h4 class="page-header"><a href="#upload">Upload Tender</a></h4>
  <p class="lead">
  On the home page of e-Tool, click on the <b>Upload Tender</b> menu at the top menu. The following page will be displayed.

  </p>
  <div class="row">
   <img src="{{asset('etoolmanualassets/images/upload.png')}}"/>
  </div><!-- /.row -->
  <p><b>Note:</b> Date and time selection is based on 24 hour format. <br/>
You can add as many tender you like by using the above page but it should be valid. Here you are provided with the relevant fields where you can add the information of a tender. This is the page from where you can upload new tender into the web page. You need to add the details in accordance to the boxes corresponding to their name on the left side. On the first box you can see the reference No. This reference No. will be entered by the concerned procuring agency.
</p>
<p>The contract period will be in months. Tentative start date and tentative end date will depend on the contract period (for example: if the contract period is 12 month, you will select the tentative start date, tentative end date will be auto calculated, 12 months from the tentative start date) and the <b>Dzongkhag</b> list shows that it is the exact location for the work to be carried on. In the <b>Filename box</b> click the <b>Choose Files</b> button to locate the tender documents at your system that is to be uploaded in the CDB website. This particular tender uploaded by you will be reflected on the cdb website at the sidebar where tender reflects. Click on <b>Cancel</b> button to cancel the process.</p>
</section>

<section id="list">
  <h4 class="page-header"><a href="#list">List of Uploaded Tender</a></h4>
  <p class="lead">
    The above page will be used to edit/delete existing users
  </p>
  <div class="row">
   <img src="{{asset('etoolmanualassets/images/list.png')}}"/>
  </div><!-- /.row -->
<p>List of uploaded tender will display the entire list of tenders that the particular procuring agencies have uploaded till date. Tender can be searched using work id, category and classification. Click on Search button to display the list and clear button to clear the filters.<br/>
This work id is automatically generated by the system in the format Procuring Agency name, following with the current year and the serial number of tenders uploaded for that particular year. The work id number will restart form 1 after every one year. The work id cannot be edited or changed with a new one unless the procuring agency is changed.
</p>
<h3>Edit</h3>
<p>To edit the information of uploaded tender, click on the <b>Edit</b> button against each list and the following page will be displayed. </p>
<div class="row">
   <img src="{{asset('etoolmanualassets/images/edit.png')}}"/>
  </div><!-- /.row -->
  
 <p>
	You can edit the tender details in case of re-tender or to make necessary correction to the tender. In case where you need to re-tender the previously tendered work, you can always use this edit link to edit the information and re-tender.<br/>
To view the uploaded tender related document, click on the link below document name or you can delete the file by clicking on the delete link.<br/>
To replace the uploaded tender related document, you just have to upload the new document and the system will replace the old document with the new one.<br/>
The details of a particular work will be displayed here in their respective boxes. You can edit any of the information whichever you feel is relevant for changes. After you are done editing you just need to click on the ‘Save’ button.

 </p> 
 <h3>Delete</h3>
<p>To delete the information of uploaded tender, click on the <b>Delete</b> button and the following pop up page will be displayed. </p>
<div class="row">
   <img src="{{asset('etoolmanualassets/images/delete.png')}}"/>
  </div><!-- /.row -->
  
 <p>
Enter the remarks and click on Delete button to delete the tender and Cancel button to cancel the process.<br/>
<b>Note:</b> After the closing date of sale of tender, the procuring agencies will not be able to edit or delete the tender.
 </p> 
</section>

<section id="criteria">
  <h4 class="page-header"><a href="#criteria">Set Criteria</a></h4>

  <div class="row">
   <img src="{{asset('etoolmanualassets/images/criteria.png')}}"/>
  </div><!-- /.row -->
	<p class="lead">
		To set criteria for each uploaded tender, you can search the tender by work Id/category/classification. <br/>
		Click on Set button to set criteria for each tender and the following page will be displayed.

	</p>
	
    <div class="row">
   <img src="{{asset('etoolmanualassets/images/criteriaone.png')}}"/>
  </div><!-- /.row -->
    <p class="lead">
   The work summary of the particular tender will be displayed at the top of the page. Below the work summary, the following tab will be displayed.
  </p>
	1. Human Resource<br/>
	2. Equipment<br/>
	This is the page where you set criteria for human resource and equipment for that particular work listed at the top of the page. With these criteria set, than only Contractors can participate or bid for a work if they are eligible.
</section>

<section id="human">
  <h4 class="page-header"><a href="#human">Human Resource</a></h4>
  <p class="lead">
   The first tab is to set criteria for human resource, the list of human resource required for the above work will be set here.  Enter all the required detail by selecting the tier and the designation from the dropdown. 
  </p>
  <h5>Validation</h5>
  Tier 1 = Total points should be 50<br/>
Tier 2 = Total points should be 30<br/>
Tier 3 = Total points should be 20<br/><br/>
The points are assigned according to the Tier whereby Tier I should have 50 points, Tier II with 30 points and Tier III with 20 points which makes a total of 100 points. You can select multiple human resources in any of the tier but should not exceed more than the points assigned above according to the tier. And suppose if you insert more than 50 points in Tier I the system will display a following message:
<br/><br/>SCREEN SHOTTTTTTTTTTTTT!!!
<br/><br/>
  This applies to all Tiers.
 
</section>

<section id="equipment">
  <h4 class="page-header"><a href="#equipment">Equipment</a></h4>
  <p class="lead">
    The criteria for equipment are being set here. This page will be loaded automatically with the details of the Equipment criteria if already set. If no equipment details has been inserted the page remains blank.
	<br/>
	For the first time the page will be blank and after adding some data into the page it will be displayed as the following page.

  </p>
  <div class="row">
	<img src="{{asset('etoolmanualassets/images/equipment.png')}}"/>
  </div><!-- /.row -->
  
  <p class="lead">
   Here there are four fields to be entered such as Tier, Equipment name, quantity required and Points. The Tier and Equipment name fields are generated automatically. You just need to select from the dropdown box whichever is relevant. And in the other two fields you need to enter the number of equipment required and the points you want to assign to that particular equipment. The points are assigned according to the Tier whereby Tier I should have 50 points, Tier II with 30 points and Tier III with 20 points which makes a total of 100 points.
	<br/>
   To add more equipment, click on + button. Click on<b> Update</b> button to set the criteria and <b>Cancel</b> button to cancel the process.

  </p>
  <p><strong>Edit</strong></strong>
    <p class="lead">
    To edit the criteria set, go back to the list of uploaded tender, click on set button against that particular tender and edit the information.
  </p>
</section>

<section id="evaluation">
  <h4 class="page-header"><a href="#evaluation">Evaluation</a></h4>
  <p class="lead">
   On this evaluation page, you see all the list of works added by you with some of their information like work id, name of work, class and category etc.  All the works will have a different work id.</p>
  <div class="row">
	<img src="{{asset('etoolmanualassets/images/evaluation.png')}}"/>
  </div><!-- /.row -->
</section>


<!-- ============================================================= -->
<section id="committee">
  <h2 class="page-header"><a href="#committee">Evaluation Committee</a></h2>
  <p class="lead">You can click on <b>Evaluation Committee</b> button and the following page will be displayed. <p>
  <div class="row">
	<img src="{{asset('etoolmanualassets/images/login.png')}}"/>
  </div><!-- /.row -->
Here you can add committee members those who will be involved in evaluating the work. And you have option to edit and delete them. <br/>
You can add more committee member by clicking the + button. To edit the name and designation, you and overwrite the old information and click on Save button. To delete the member, click on X button.

</section>
<section id="awarding">
  <h2 class="page-header"><a href="#awarding">Awarding Committee</a></h2>
  <p class="lead">You can click on <b>Awarding Committee</b> button and the following page will be displayed. <p>
  <div class="row">
	<img src="{{asset('etoolmanualassets/images/awarding.png')}}"/>
  </div><!-- /.row -->
The process to add/edit/delete awarding committee is same as that as evaluation committee. Click on Back button to go back to the Evaluation main page.
</section>

<!-- ============================================================= -->

<section id="details">
  <h2 class="page-header"><a href="#details">Details</a></h2>
  <p class="lead">
    Click on Details button and the following page will be displayed.
  </p>
   <div class="row">
	<img src="{{asset('etoolmanualassets/images/detail.png')}}"/>
  </div><!-- /.row -->
 
</section>



<section id="contractor">
  <h4 class="page-header"><a href="#contractor">Add Contractor</a></h4>
  <p class="lead">
    This is the place where you insert the details of a contractor. When you click on the Add Contractor button the following page opens up to you. Note that when you add a contractor there are two methods of entering according to the joint venture. If he/she is not participating as a joint venture you enter the details of a single contractor but if they are participating as a joint venture then you need to enter the details of both the contractors. 
<br/>
The following pop up diagram shows the page for a single contractor where he is not a joint venture. You need to choose the joint venture field from the diagram below.

  </p>
     <div class="row">
	<img src="{{asset('etoolmanualassets/images/contractor.png')}}"/>
  </div><!-- /.row -->
  <p>
	Here you enter the information of a single contractor on the relevant fields seen above. Note that the joint venture button has been selected as “NO”.<br/><br/>
The following page will be displayed if a contractor is participating as a joint venture.
  </p>
     <div class="row">
	<img src="{{asset('etoolmanualassets/images/contractorone.png')}}"/>
  </div><!-- /.row -->
  <p>
	Here the “Yes” button is selected on the joint venture field and you will notice that you need to enter the details of more than one contractor. There are more fields to be entered whereby you enter CDB number for all the contractors as well as the percentage stake and enter the credit line available for all the contractors.<br>
Once you are finished entering the details of a contractor click on the “SAVE” button at the end. Then the following page will be displayed.
</p><br/>
<h3>Edit Contractor</h3>
<p>
Click on Edit Contractor button to edit information of the contractor. Click on Back button to go back to the work details. <br>
Once you have added the details of a contractor, you need to enter the Equipment and Human Resource details that a particular procuring agency has provided for a particular work. This will be done according the criteria set before.
</p>
	<h4><b>Human resource </b></h4>
	<p>
		This is the page where you add the human resource details for a particular contractor. On the top of the page the Human Resource Criteria that has been set before will be displayed. Below the criteria, the Human Resource details provided by that contractor will be displayed according to the Tier and the last part is where you insert the list of Human Resource. On the insert part you need to first enter the CID card number as a proof, then select Tier, designation and the Qualification set earlier will be displayed accordingly on the qualification field. On the Human Resource name field, all the Human Resources that have been set will be available. You just need to select accordingly and his/her qualification/designation will also be displayed respectively. The point that was set earlier will also be auto displayed.
		<br/>
		Before saving you can check ID Card Number by clicking Check button. When you click, popup message (i.e., CDB No. and Work ID) will show only if ID Card Number is being used under some other contractor.<br>
		Once you are done entering all the information of Human Resources, click on equipment tab and enter the details.

	</p>
	<h4><b>Equipment</b></h4>
	 <div class="row">
	<img src="{{asset('etoolmanualassets/images/nono.png')}}"/>
  </div><!-- /.row -->
	<p>
		This is the page where you add the equipment details for a particular contractor. There are five fields on this page. On the top of the page the Equipment Criteria that has been set before by the procuring agency will be displayed. Below the criteria, the equipment details provided by that contractor will be displayed. On the name field, all the equipment that has been set will be available. You just need to select accordingly. <br/>
You can check the registration number for equipment by clicking “Check” button. When you click on it, a popup message (i.e., CDB No. and Work ID) will show only if the registration number for that equipment is being used under some other contractor, that’s under work. Once you are done entering all the information of equipment, click on the “Save” button at the end of the page. <br/>
Once you are finished saving the details of the equipment, click on the “Back” button on the left side of the page.

	</p>
</section>


<!-- ============================================================= -->

<section id="result">
  <h2 class="page-header"><a href="#result">Process Result</a></h2>
  <p class="lead">After adding all the contractors’ detail, then click on Process Result button to process the result. For the first time before evaluation takes place, result will remain as “Process”, as the following page.</p>
	 <div class="row">
	<img src="{{asset('etoolmanualassets/images/result.png')}}"/>
  </div><!-- /.row -->
  <p>Click on Process Result button to process the result. <br/>
Here you can know who scored the highest point and it is denoted by H1, second highest by H2 and third by H3 and so on. And also you can see how many contractors was Not Qualified (Scored less than 65 points). Now you can click on the Details link at the end to view how the points have been scored in technical scoring.
</p>
	<h4>Score Details</h4>
	
	 <div class="row">
	<img src="{{asset('etoolmanualassets/images/score.png')}}"/>
  </div><!-- /.row -->
  <p>Here all the points scored for a contractors Capability, Capacity and Preference Score will be displayed here. To go back to the previous page simply click on the Back button at the end of the page and you will be redirected back to the evaluation page.</p>
  
  <h4>Result Reset</h4>
  <p>On clicking the reset result button, the result will be set back to “Process” as shown by the arrow below: <br/>
You can evaluate again by the clicking the Process button or you can add more list of contractors and by entering their details for Equipment and human resource.
</p>
  <div class="row">
	<img src="{{asset('etoolmanualassets/images/reset.png')}}"/>
  </div><!-- /.row -->
  
  <h4>Award</h4>
  <p>Click on Award button to award the work to the winning bidder upon the committee’s decision. After the work has been awarded, the status will be changed to Awarded. </p>
	
	<h4>View Reports<h4>
	<p>On clicking this button you will be able to view the entire report of all the contractors for a particular work. The following page opens up to you:<br/>
Here you will know how many contractors have participated and can view full details how much a contractor scored. You will also be able to find out the Technical and Financial Score of a particular contractor as well as his preference score. You can also view the details of the Evaluation committee members.
</p>
  </section>
  
  <section id="completion">
	  <h2 class="page-header"><a href="#completion">Completion</a></h2>
	<p>Here the list of the work that has been evaluated and the work that has been awarded to any one of the contractor will be displayed. This page has to correspond with the Evaluation page. Only after the work has been awarded to a contractor, the details will be listed here. This is the conclusion page whereby you need to enter the final scoring for that particular work. And once you enter the final score, it means the work has been “Completed” and the details will not be seen any more on this page.</P>
	<div class="row">
	<img src="{{asset('etoolmanualassets/images/completion.png')}}"/>
  </div><!-- /.row -->
  <p>
	You can search the detail by entering work Order No. or work start date.
  </p>
  </section>
 

        </div><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>CDB</b>
        </div>
        <strong>Copyright &copy; 2015 <a href="http://www.cdb.gov.bt">CDB</a>.</strong> All rights reserved.
      </footer>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="{{asset('etoolmanualassets/plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{asset('etoolmanualassets/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{asset('etoolmanualassets/plugins/fastclick/fastclick.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('etoolmanualassets/dist/js/app.min.js')}}"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="{{asset('etoolmanualassets/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
    <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
    <script src="{{asset('etoolmanualassets/documentation/docs.js')}}"></script>
  </body>
</html>