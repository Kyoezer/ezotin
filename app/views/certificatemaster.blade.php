<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Construction Development Board</title>
        <link rel="shortcut icon" href="{{asset('img/favicon.png')}}">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <style>
			body {
			  font-family: cambria;
			  margin:0;
			  padding:0;
				/*background: url('../../public/uploads/certbg.png') no-repeat center center fixed;*/
				/*-webkit-background-size: cover;*/
				/*-moz-background-size: cover;*/
				/*-o-background-size: cover;*/
				/*background-size: cover;*/
				/*background-size: 1000px 1000px;*/
				
			}
			/*-------Print CSS---------*/
			div.certificatebanner{
				width:100%;
				margin:0 auto;
				text-align: center;
			}

			div.bg{
				width:100%;
				margin:0 auto;
				text-align: center;
			}
			p{
				z-index: 9999;
			}
			div.centerdiv{
				left:50%;
			}
			.hr{
			    display: block;
			    height: 0;
			    overflow: hidden;
			    font-size: 0;
			    border-top: 1px solid #e3e3e3;
			    margin: 12px 0;
			  }
			p.heading{
			  margin:0;
			  padding:10px 0 5px 0;
			  font-weight: bold;
			  font-size: 11pt;
			}
			.printtitle{
			    margin:0;
			    padding:0 0 5px 0;
			    font-weight: bold;
			    font-size: 12pt;
			    text-decoration: underline;
			}
			.text-right {  
			  text-align: right;
			}
			.text-left {  
			  text-align: left;
			}
			.text-center{
			  text-align: center;
			}
			.x-x-small{
			  width:5%;
			}
			.x-small{
			  width:10%;
			}
			.small-s{
			  width:15%;
			}
			.small{
			  width:20%;
			}
			.small-medium{
			  width:30%; 
			}
			.x-small-medium{
			  width:25%; 
			}
			.medium{
			  width:50%; 
			}
			table.data{
			  border-collapse:collapse;
			  width: 100%;
			  /*background: #fff;*/
			  font-size: 10pt;
				z-index: 9999;
			}
			table.data th{
			  background:#d7e5f2;
			  padding:3px 0;
			}
			table.data td{
			    font-size: 10pt;
			    padding:2px;
			    /*background:#fff;*/
			}
			table.data td.number{
			  text-align: right !important;
			}
			table.data tr {
			  /*background: #fff;*/
			}

			table.data tr:nth-child(odd) {
			  /*background: #ccc;*/
			}
			table.data-large{
			  border-collapse:collapse;
			  width: 100%;
			  /*background: #fff;*/
			  font-size: 10pt;
				z-index: 9999;
			}
			table.data-large th{
			  /*background:#d7e5f2;*/
			  border:1px solid #000;
			}
			table.data-large td{
			    font-size: 10pt;
			    border:1px solid #000;
			    padding:2px;
			    /*background:#fff;*/
			}
			table.data-large tr {
			  /*background: #fff;*/
			}

			table.data-large tr:nth-child(odd) {
			  /*background: #ccc;*/
			}
			table.with-outer-border-only{
				width:100%;
				border: 1px solid #000;
				z-index: 9999;
			}
			table.with-outer-border-only td{
			    font-size: 10pt;
			    padding:2px;
			    /*background:#fff;*/
			}
			table.with-outer-border-only td.number{
			  text-align: right !important;
			}
			table.with-outer-border-only tr {
			  /*background: #fff;*/
			}

			table.with-outer-border-only tr:nth-child(odd) {
			  /*background: #ccc;*/
			}
			table.data-small-with-border{
                /*display: inline;*/
			  border-collapse:collapse;
			  width: 50%;
			  /*background: #fff;*/
			  font-size: 10pt;
				z-index: 9999;
			}
			table.data-small-with-border th{
			  background:#d7e5f2;
			  border:1px solid #000;
			}
			table.data-small-with-border td{
			    font-size: 10pt;
			    border:1px solid #000;
			    padding:2px;
			    /*background:#fff;*/
			}
			table.data-small-with-border tr {
			  /*background: #fff;*/
			}
			table.data-small-with-border tr:nth-child(odd) {
			  /*background: #ccc;*/
			}
			p.description{
			  font-size: 14px;
			  line-height: 35px;
				z-index: 99999;
			}
			.row{
				width:100%;
			}
			.col-md-6{
				width:50%;
				float:left;
			}
			.table-smallfont td{
				font-size: 8pt;
			}
			.table_lessspace td{
				padding: 1px!important;
			}
			.certificate-detail{
				color: #000080;
				font-size: 11pt!important;
			}
			.sign {
      position: absolute;
      top: 870px;
      left: 270px;
    }
	.sign1 {
      position: absolute;
      top: 920px;
      left: 120px;
    }

	.note{
		color: red;
				
			}


        </style>
	</head>
	<body>
		<img src="{{asset('uploads/certbg.png')}}" style="position: absolute; width: 105%; height: 1050px; margin-left: -15px; margin-top: -2px;"/>
		<br />
		<div class="certificatebanner">
			<img src="{{URL::to('assets/cdb/layout/img/certificate.png')}}" style="margin-top: -10px;"/>
			@if(!isset($nonBhutanese))
				<br/><br>
			@else
				@if(!$nonBhutanese)
					<br/><br>
				@endif
			@endif
		</div>
		@yield('content')
		<br />
		<div class="text-center">
			<img src="{{asset('uploads/dg.png')}}" width="160" style="margin-top: -34px;" class = "sign"/>
		
			<p class="sign1" style="margin-top: -2px;">(Director)<br/>
			Construction Development Board<br/><br/>
			<small>
				Tel No.: +975-2-326035/333502. Fax No.:+975-2-321989. Post Box # 1349<br/>
				E-mail : registration@cdb.gov.bt. Website : www.cdb.gov.bt
			</small>
			</p>
			

		</div>
	

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<center><h4>Terms & Conditions of CDB Certification</h4></center>

<p>i.The holder of registration certificate issued by CDB is qualified to participate in public
procurement procedure.</p>
<p>ii.The issuance of registration certificate will be based largely on the fulfillment of the
minimum criteria set against classification and categorization as applicable and upon
certification by competent authority for construction.</p>
<p>iii.Registration certificate holder is responsible for complying with the CDB Manual, relevant
Guidelines and other legal instruments issued from time to time by CDB and any other
relevant laws during the validity period.</p>
<p>iv.Without compromising the individual rights and the prevailing laws, CDB reserves the
right to use and disclose such information in any form of publication in the interest of
general public.</p>
<p>v.CDB will not be accountable for any or fabricated submission that could have led to the
fulfillment of the criteria and subsequent issue of registration.</p>
<p>vi.Applicant shall be liable for penalty for forgery of documents as mentioned in the Penal
Code of Bhutan and any other relevant laws.</p>
<p>vii.Registration certificate once issued would not relieve the certificate holder of any
relaxation on the minimum requirements for registration.</p>
<p>viii.In pursuant to the provisions of Companies Act of Bhutan and other applicable laws, the
certificate issued is non-transferable even if the promoters separate and establish similar
companies.</p>
<p>ix.Registration certificate cannot be leased or subleased to any individual or</p>
<p>x.Registration certificate is valid during the period for which it was issued provided it has not
been cancelled, de-registered, suspended or revoked by CDB or any other competent
authority.</p>
<p>xi.Firms failing to renew registration certificate after 13 months from the expiry date will be
de-registered. 13 months hereby includes one month of grace period provided to the
registrants.</p>
<p>xii.De-registered applicant remains in force for two years from the date of deregistration
except for those applicants who could not renew despite</p>
<p>xiii.Failing to renew registration certificate within the expiry date will lead to penalty of
Nu.100 per day and renewal of the registration certificate shall be applied after thirty days of
its expiry.</p>
<p>xiv.Failing to pay the fees for approved application within 30 days shall lead to cancellation
of the application.</p>
<p>xv.All registered construction firm or other registrants as may be applicable shall produce
certificate of the mandatory course in order to apply for renewal of the registration
certificate.</p>
<p>xvi.Any registrants as may be applicable may apply online using Application Form for
upgrading of his category and or for additional classification, categories and sub-categories:</p>
<ul>a. at any time during the validity of certificate; or</ul>
<ul>b. at the time of renewal.</ul><br/><br/><br/>
<p>xvii.No provisional certificates shall be issued except for the following if the registration
certificate has expired:</p>
<ul>a. Issuance or renewal of labour permit; or</ul>
<ul>b. Issuance or renewal of engineer's permit.</ul>
<p>xviii.No contractor can submit bid, participate in bidding or be on the contention</p>
<p>xix.No contractor can undertake works which is not within the scope of the registration.</p>
<p>xx.CDB may verify the resources committed for the projects as and when desires.</p>
<p>xxi.Large and medium contractors and consultants shall have office estabulshed with
signboard and requirements determined by CDB.</p>
<p>xxii.Registrants as may be appulcable, shall update any changes onulne in their</p>
<p>xxiii.The registration is subject to verification whenever the CDB so desires for which the
mandatory requirement of manpower and equipment as may be</p>
<p>xxiv.The CDB Registration Certificate shall be downgraded, suspended or</p>
<ul>1.Holder has contravened provisions of this Guideulne, other Guideulnes and any
other relevant laws in force;</ul>
<ul>2.Holder undertakes unlawful participation in the procurement process;</ul>
<ul>3.Holder does not possess the minimum requirements prescribed by CDB during the physical verification;</ul>
<ul>4.Holder has obtained the same due to false submissions;</ul>
<ul>5.Holder becomes bankrupt or winds up;</ul>
<ul>6.Holder has been adjudged unsound mind by a competent court;</ul>
<ul>7.Holder engages in fronting;</ul>
<ul>8.Holder has been found guilty of professional misconduct after an inquiry held; or</ul>
<ul>9.Holder has been charged by the court for penal offence</ul><br/><br/><br/><br/><br/><br/>

<p style="text-align:justify;" class = "note" >Note: This is computer generated CDB Registration Certificate is meant solely for the purpose of bidding for
the works. The Procuring Agency are to scrutinize and accept this certificate. Construction Development
Board shall not be responsible for misuse of this document or alteration or distortion of information contained
in this document by respective firms. The Procuring Agency may verify the authenticity of the document with
the CDB incase of any suspicion.</p>

	</body>
</html>