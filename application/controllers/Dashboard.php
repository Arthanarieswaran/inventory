<?php 

class Dashboard extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();
		$this->data['page_title'] = 'Dashboard';
		
		$this->load->model('model_products');
		$this->load->model('model_orders');
		$this->load->model('model_users');
		$this->load->model('model_stores');
		$this->load->model('model_invoice'); 
		$this->load->model('model_customer_history');
		$this->load->model('model_users');
		$this->load->model('model_vendor_history');
		$this->load->helper('language'); 
		$this->load->model('model_receipt'); 
		$userID = $this->session->userdata('id');
		$userData = $this->model_users->getSingleUser($userID);
		if(count($userData) > 0){
			$this->lang->load("content", $userData[0]['language']);
		}else{
			$this->lang->load("content", 'english');
		}
        
	}

	/* 
	* It only redirects to the manage category page
	* It passes the total product, total paid orders, total users, and total stores information
	into the frontend.
	*/
	public function index()
	{
		
		$gg = $this->input->post('dataType');
		$dataType = 'today';
		if($gg){
			$dataType = $gg;
		}
		// $this->data['total_products'] = $this->model_products->countTotalProducts($dataType);
		$this->data['total_paid_orders'] = $this->model_orders->getTotalOrders($dataType);
		// $this->data['total_users'] = $this->model_users->countTotalUsers($dataType);
		// $this->data['total_stores'] = $this->model_stores->countTotalStores($dataType);

		$this->data['balance'] = $this->model_customer_history->countCustomerBalance($dataType);

		$this->data['total_invoice'] = $this->model_invoice->getTotalInvoice($dataType);
		$this->data['total_category'] = $this->model_products->countTotalcategory();
		$this->data['total_attribures'] = $this->model_products->countTotalattribures();
		// $this->data['total_stores'] = $this->model_stores->countTotalStores();

		$this->data['cash'] = $this->model_orders->getAmountCash($dataType);
		$this->data['cheque'] = $this->model_orders->getAmountCheque($dataType);
		$this->data['online'] = $this->model_orders->getAmountOnline($dataType);

		$this->data['cash1'] = $this->model_invoice->getAmountCash($dataType);
		$this->data['cheque1'] = $this->model_invoice->getAmountCheque($dataType);
		$this->data['online1'] = $this->model_invoice->getAmountOnline($dataType);

		$invoiceId = $this->model_invoice->getInvoiceDetails($dataType);
		$invoiceID = [];
		foreach ($invoiceId as $key => $value) {
			array_push($invoiceID,$value['id']);
			}

		$this->data['invoiceItem'] = $this->model_invoice->getInvoiceItemDetails($invoiceID);

		$quotationId = $this->model_orders->getQuotationDetails($dataType);
		$quotationID = [];
		foreach ($quotationId as $key => $value) {
			array_push($quotationID,$value['id']);
			}

		$this->data['quotationItem'] = $this->model_orders->geQuotationItemDetails($quotationID);
		$this->data['vendordetails'] = $this->model_vendor_history->getVendorDataDetails($dataType);
		$this->data['receiptItem'] = $this->model_receipt->getReceiptItemDetails($dataType);
		$user_id = $this->session->userdata('id');
		$is_admin = ($user_id == 1) ? true :false;
      
		$this->data['is_admin'] = $is_admin;
		$this->render_template('dashboard', $this->data);
	}
	
	public function dataUpdate()
	{
		
		$gg = $this->input->post('dataType');
		$dataType = 'today';
		if($gg){
			$dataType = $gg;
		}

		// $this->data['total_products'] = $this->model_products->countTotalProducts($dataType);
		$data = $this->model_orders->getTotalOrders($dataType);
		// $this->data['total_users'] = $this->model_users->countTotalUsers($dataType);
		// $this->data['total_stores'] = $this->model_stores->countTotalStores($dataType);

		$this->data['balance'] = $this->model_customer_history->countCustomerBalance($dataType);
		$this->data['receiptItem'] = $this->model_receipt->getReceiptItemDetails($dataType);
		$order  = $this->model_invoice->getTotalInvoice($dataType);
		$this->data['total_category'] = $this->model_products->countTotalcategory();
		$this->data['total_attribures'] = $this->model_products->countTotalattribures();
		// $this->data['total_stores'] = $this->model_stores->countTotalStores();

		$this->data['cash'] = $this->model_orders->getAmountCash($dataType);
		$this->data['cheque'] = $this->model_orders->getAmountCheque($dataType);
		$this->data['online'] = $this->model_orders->getAmountOnline($dataType);

		$this->data['cash1'] = $this->model_invoice->getAmountCash($dataType);
		$this->data['cheque1'] = $this->model_invoice->getAmountCheque($dataType);
		$this->data['online1'] = $this->model_invoice->getAmountOnline($dataType);

		$invoiceId = $this->model_invoice->getInvoiceDetails($dataType);
		$invoiceID = [];
		foreach ($invoiceId as $key => $value) {
			array_push($invoiceID,$value['id']);
			}

		$this->data['invoiceItem'] = $this->model_invoice->getInvoiceItemDetails($invoiceID);

		$quotationId = $this->model_orders->getQuotationDetails($dataType);
		$quotationID = [];
		foreach ($quotationId as $key => $value) {
			array_push($quotationID,$value['id']);
			}

		$this->data['quotationItem'] = $this->model_orders->geQuotationItemDetails($quotationID);

		$result = [];
		foreach ($data as $key => $value) {
		$results = array(
			date('d-m-Y',$value['date_time']),
			$value['bill_no'],
			$value['name'],
			$value['address1'],
			$value['address2'],
			$value['taluk'],
			$value['mobile'],
			$value['net_amount'],
			$value['orderid'],
		);
		array_push($result,$results);
	    }
		$this->data['total_paid_orders'] = $result;

		$invoice = [];
		foreach ($order as $key => $value) {
		$invoices = array(
			date('d-m-Y',$value['date_time']),
			$value['bill_no'],
			$value['name'],
			$value['address1'],
			$value['address2'],
			$value['taluk'],
			$value['mobile'],
			$value['net_amount'],
			$value['orderid'],
		);
		array_push($invoice,$invoices);
	    }
		$this->data['total_invoice'] = $invoice;
		$user_id = $this->session->userdata('id');
		$is_admin = ($user_id == 1) ? true :false;

		$this->data['is_admin'] = $is_admin;
		echo json_encode($this->data);
	}



	public function dataCustom()
	{
		
		$dataType = $this->input->post('dataType');
		$fromdate = $this->input->post('fromdate');
		$todate = $this->input->post('todate');
		// $this->data['total_products'] = $this->model_products->countTotalProducts($dataType);
		$data = $this->model_orders->getTotalOrdersCustom($dataType,$fromdate,$todate);
		// $this->data['total_users'] = $this->model_users->countTotalUsers($dataType);
		// $this->data['total_stores'] = $this->model_stores->countTotalStores($dataType);

		$this->data['balance'] = $this->model_customer_history->countCustomerBalanceCustom($dataType,$fromdate,$todate);

		$order  = $this->model_invoice->getTotalInvoiceCustom($dataType,$fromdate,$todate);
		$this->data['total_category'] = $this->model_products->countTotalcategory();
		$this->data['total_attribures'] = $this->model_products->countTotalattribures();
		// $this->data['total_stores'] = $this->model_stores->countTotalStores();

		$this->data['cash'] = $this->model_orders->getAmountCashCustom($dataType,$fromdate,$todate);
		$this->data['cheque'] = $this->model_orders->getAmountChequeCustom($dataType,$fromdate,$todate);
		$this->data['online'] = $this->model_orders->getAmountOnlineCustom($dataType,$fromdate,$todate);

		$this->data['cash1'] = $this->model_invoice->getAmountCashCustom($dataType,$fromdate,$todate);
		$this->data['cheque1'] = $this->model_invoice->getAmountChequeCustom($dataType,$fromdate,$todate);
		$this->data['online1'] = $this->model_invoice->getAmountOnlineCustom($dataType,$fromdate,$todate);
		$invoiceId = $this->model_invoice->getInvoiceDetailsCustom($dataType,$fromdate,$todate);
		$invoiceID = [];
		foreach ($invoiceId as $key => $value) {
			array_push($invoiceID,$value['id']);
			}

		$this->data['invoiceItem'] = $this->model_invoice->getInvoiceItemDetails($invoiceID);

		$quotationId = $this->model_orders->getQuotationDetailsCustom($dataType,$fromdate,$todate);
		$quotationID = [];
		foreach ($quotationId as $key => $value) {
			array_push($quotationID,$value['id']);
			}

		$this->data['quotationItem'] = $this->model_orders->geQuotationItemDetails($quotationID);
		$result = [];
		foreach ($data as $key => $value) {
		$results = array(
			date('d-m-Y',$value['date_time']),
			$value['bill_no'],
			$value['name'],
			$value['address1'],
			$value['address2'],
			$value['taluk'],
			$value['mobile'],
			$value['net_amount'],
			$value['orderid'],
		);
		array_push($result,$results);
	    }
		$this->data['total_paid_orders'] = $result;

		$invoice = [];
		foreach ($order as $key => $value) {
		$invoices = array(
			date('d-m-Y',$value['date_time']),
			$value['bill_no'],
			$value['name'],
			$value['address1'],
			$value['address2'],
			$value['taluk'],
			$value['mobile'],
			$value['net_amount'],
			$value['orderid'],
		);
		array_push($invoice,$invoices);
	    }
		$this->data['total_invoice'] = $invoice;
		$user_id = $this->session->userdata('id');
		$is_admin = ($user_id == 1) ? true :false;

		$this->data['is_admin'] = $is_admin;
		echo json_encode($this->data);
	}


	public function saleAnalysis()
	{
		$months = array(1 => 'Jan,', 2 => 'Feb,', 3 => 'Mar,', 4 => 'Apr,', 5 => 'May,', 6 => 'Jun,', 7 => 'Jul,', 8 => 'Aug,', 9 => 'Sep,', 10 => 'Oct,', 11 => 'Nov,', 12 => 'Dec,');
		$gg = $this->input->post('dataType');
		$dataType = 'year';
         $currentMonth = date('m');
		// $invTotal = $this->model_orders->getTotalAmt($dataType);
		// $quoTotal = $this->model_invoice->getTotalAmt($dataType);
		
		$totalAmt = '';
		$paidAmt = '';
		$balAmt = '';
		$monthList = '';
		for($i=1;$i<= $currentMonth;$i++){
			$eachTotal = 0;
			$eachPaid = 0;
			$quoTotal = $this->model_orders->getTotalAmt($i);
			$invTotal = $this->model_invoice->getTotalAmt($i);

			$quoPaid = $this->model_orders->getPaidAmt($i);
			$invPaid = $this->model_invoice->getPaidAmt($i);
			
			if($invTotal[0]['net_amount']){
				$eachTotal = ($eachTotal + $invTotal[0]['net_amount']);
				
			}
			if($quoTotal[0]['net_amount']){
				$eachTotal = ($eachTotal + $quoTotal[0]['net_amount']);
			}

			if($invPaid[0]['paid_amount']){
				$eachPaid = ($eachPaid + $invPaid[0]['paid_amount']);
				
			}
			if($quoPaid[0]['paid_amount']){
				$eachPaid = ($eachPaid + $quoPaid[0]['paid_amount']);
			}
			$eachBal = $eachTotal - $eachPaid;
			if($eachBal < 0){
				$eachBal1 = abs($eachBal);
				$eachBal = number_format($eachBal1,'2').'(-)';
			}else{
				$eachBal1 = abs($eachBal);
				$eachBal = number_format($eachBal1,'2').'(+)';
			}
			$totalAmt .=  number_format($eachTotal,'2').',';
			$paidAmt .=  number_format($eachPaid,'2').',';
			$balAmt .= $eachBal.',';
			$monthList .= $months[$i];
		}
		$this->data['total'] = [$totalAmt];
		$this->data['paid'] = [$paidAmt];
		$this->data['balance'] = [$balAmt];
		$this->data['month'] = [$monthList];
		echo json_encode($this->data);
	}



	public function printDiv(){

		
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
			
				// $order_datas = $this->model_orders->getPrintInvoice();
				// 	$order_data = $order_datas[0];
				// $orders_items = $this->model_orders->getOrdersItemData();
				// $customerData= $this->model_customer_history->getCustomerSingleData($order_data['customer_id']);
				
				// $company_info = $this->model_company->getCompanyData(1);
				
				// $order_date = date('d/m/Y', $order_data['date_time']);
				// $imageUrl = '.base_url./assets/images/murugan.png';
				
				

				
				$html = '<!-- Main content -->
				<!DOCTYPE html>

		<html>
		<head>
		  <meta charset="utf-8">
		  <meta http-equiv="X-UA-Compatible" content="IE=edge">
		  <title>Quotation</title>
		  <!-- Tell the browser to be responsive to screen width -->
		  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		  <!-- Bootstrap 3.3.7 -->
		  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
		  <!-- Font Awesome -->
		  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
		  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
		</head>
		<style>
		table {
			width: 100%;
		  }
		  
		  table.print-content {
			font-size: 12px;
			border: 1px solid #dee2e6;
			border-collapse: collapse !important;
		
		  }
		  
		  table.print-content th,
		  table.print-content td {
			padding: .2rem .4rem;
			text-align: left;
			vertical-align: top;
			border-top: 1px solid #dee2e6;
		  }
		  
		  @media print {
			.pageFooter {
				display: block;
			  position: fixed;	
			  bottom: 0;
			  left: 0;
			}
		  }
			</style>
		<body onload="window.print();">
		<div >
		<table >
<!-- Start Header -->
<thead>
<tr>
<td>
<img src="'.base_url('assets/images/invoiceheadta.png').'">
</td>
</tr>
</thead>
<!-- End Header -->
<tr>
<td>
			<table class="table table-striped table-bordered print-content">
			  <thead>
			  <tr>
			  <div class="row invoice-info">
					  
			  <div class="mt-2" style="text-align:center;margin-top: 2vh;margin-bottom:1vh">
				  <b> QUOTATION<b>
			  </div>
			  <div class="col-md-6 invoice-col" style="text-align:right;">
			  <!-- <script>
			  n =  new Date();
			  y = n.getFullYear();
			  m = n.getMonth() + 1;
			  d = n.getDate();
			  document.getElementById("date").innerHTML = m + "/" + d + "/" + y;
			  </script>
				<b> Date :</b> <b id="date"></b><br>
			  </div>
			   /.col -->
			</div>
			<hr style="padding:0px;margin:0px;">
			  <div class="row invoice-info">
				
			
				<!-- /.col -->
			  </div>
			  </tr>
			  <tr>
				<th rowspan="2" style="padding: 0 6px 0 6px">S.No</th>
				<th rowspan="2" style="padding: 0 6px 0 6px">Date</th>		
				<th rowspan="2" style="padding: 0 6px 0 6px">Bill No</th>
				<th rowspan="2" style="padding: 0 6px 0 6px">Customer Name</th>
				<th rowspan="2" style="padding: 0 6px 0 6px">Address</th>
				<th rowspan="2" style="padding: 0 6px 0 6px">Mobile Number</th>
				<th rowspan="2" style="padding: 0 6px 0 6px">Total</th>
			  </tr>
			  </thead>
			  <thead>
			</thead>   
                               

			 
			 
			</table>
</td>
</tr>

</table>
</div>

<div class="col-md-12" style="margin-left : 45em;">
<p>Sign</p>	
</div>
	</body>
	<script>
window.onafterprint = function(event) {
	window.close();
};
</script>
</html>';
	
				  echo $html;
				 
		}
	}
		
	





