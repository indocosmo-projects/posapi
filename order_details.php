<?php
//ini_set("display_errors", 1);

//require 'vendor/autoload.php';
//use \Firebase\JWT\JWT;

//include headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: application/json; charset=utf-8");

// including files
include_once("db_connect.php");
if($_SERVER['REQUEST_METHOD'] === "POST"){

   // body
   $data = json_decode(file_get_contents("php://input"));

  //$headers = getallheaders();
	if(!empty($data->shop_code)){

     try{
		 
			/* $jwt = $headers["Authorization"];
			if (preg_match('/Bearer\s(\S+)/', $headers["Authorization"], $matches)) {
				$jwt = $matches[1];
			}
			$secret_key = "owt125";

			$decoded_data = JWT::decode($jwt, $secret_key, array('HS512'));
			 */
			$created_on  =$data->created_on;
			$created_at  =date("Y-m-d H:i:s", strtotime($data->created_at));
			$shop_code  =$data->shop_code;
	  

			//$sql_check="SELECT *from shop_db_settings where shop_id='".$shop_id."' ";
			$sql_check="select * from shop_db_settings where shop_id = (select id from shop where code = '$shop_code')";

			$result =  $conn->query($sql_check);

			if($result->num_rows< 1) {
				http_response_code(200);
				echo json_encode(array(
                "status" => 200,
                "message" =>'No Shops Found'
              ));
           
			} else {
					$shops=array();
					$row =  $result->fetch_assoc();
					// while($row =  $result->fetch_assoc() ) {
					//$servername = $row['db_server'];
					$username = $row['db_user'];
					$password = $row['db_password'];
					$database=$row['db_database'];
					
					// Create connection
					$conn = new mysqli($servername, $username, $password,$database);
					// Check connection
					if ($conn->connect_error) {
					  //die("Connection failed: " . $conn->connect_error);
					  http_response_code(500); //server error
						echo json_encode(array(
						 "status" => 0,
						 "message" => 'Connection failed'
					   ));
					}
					//echo "Connected successfully";
					/***********************************************/
					
					$sql_check="SELECT t1.*,t3.customer_name,t3.customer_phone,t3.customer_email,t3.id as cus_id FROM online_order_hrds as t1 LEFT JOIN
					online_order_customer AS t3 ON t3.order_id=t1.Id
					WHERE t1.order_date>='".$created_at."' AND t1.shop_code='".$shop_code."' and t1.status='1' ";
					//print_r($sql_check);exit();
					$result =  $conn->query($sql_check);

					if($result->num_rows< 1) {
						http_response_code(404);
						echo json_encode(array(
						"status" => 404,
						"message" => "No orders found"
						));
           
					} else {
						$items_details=array();
						$items=array();$payment_count=1;$food_count=1;
						while($row =mysqli_fetch_array($result, MYSQLI_ASSOC)) {
							$ss=array();$pay=array();
							$food=array();
							$status=$row['status'];
							if(trim($row['status'])=='accepted'){
								$status='accepted' ;
							}elseif(trim($row['status'])==3){
								$status= '3';
							}
							elseif(trim($row['status'])==4){
								$status='4';
							}
							elseif(trim($row['status'])=='delivered'){
								$status= '7';
							}
							elseif(trim($row['status'])=='cancelled'){
								$status='8';
							}
							// $ss['id']=$row['id'];
							$ss['order_id']=$row['online_order_id'];
							$ss['order_no']=$row['Id'];
							$ss['shop_code']=$row['shop_code'];
							$ss['station_code']='OO';
							$ss['user_id']="2";
							
							
							$ss['order_date']=date("Y-m-d",strtotime($row['order_date']));
							$ss['order_time']=$row['order_date'];
							$ss['shift_id']="0";
							$ss['customer_id']=NULL;
							$ss['is_ar_customer']="false";
							$ss['detail_total']="0.00";
							$ss['total_tax1']="0.00";
							$ss['total_tax2']="0.00";
							$ss['total_tax3']="0.00";
							$ss['total_gst']="0.00";
							$ss['total_sc']="0.00";
							$ss['total_detail_discount']=number_format($row['total_discount'],2);
							$ss['final_round_amount']="0.00";
							$ss['total_amount']=number_format($row['total_amount'],2);
							$ss['total_amount_paid']=NULL;
							$ss['total_balance']="0.00";
							$ss['actual_balance_paid']="0.00";
							$ss['cash_out']="0.00";
							$ss['remarks']=NULL;
							$ss['closing_date']=date("Y-m-d");
							$ss['closing_time']=date("Y-m-d H:i:s");
							$ss['status']=$status;
							$ss['total_print_count']=0;
							$ss['refund_total_tax1']="0.00";
							$ss['refund_total_tax2']="0.00";
							$ss['refund_total_tax3']="0.00";
							$ss['refund_total_gst']="0.00";
							$ss['refund_total_sc']="0.00";
							$ss['refund_amount']="0.00";
							$ss['sync_message']=NULL;
							$ss['created_by']="2";
							$ss['created_at']=date("Y-m-d H:i:s");
							$ss['updated_by']="2";
							$ss['updated_at']=date("Y-m-d H:i:s");
							$ss['sync_status']="0";
							$ss['bill_less_tax_amount']="0.00";
							$ss['bill_discount_amount']="0.00";
							$ss['serving_table_id']=NULL;
							$ss['served_by']="2";
							$ss['service_type']="12";
							$ss['covers']=NULL;
							
							
							//$ss['order_hdrs']['total_amount']=$row['total_amount'];
							//$ss['order_hdrs']['total_discount']=$row['total_discount'];
							//$ss['order_hdrs']['total_tax']=$row['total_tax'];
							//$ss['order_hdrs']['remarks']=$row['remarks'];
							//$ss['order_hdrs']['status']=$row['status'];

							$ss['order_customer']['order_id']=$row['online_order_id'];
							$ss['order_customer']['title']=NULL;
							$ss['order_customer']['first_name']=$row['customer_name'];
							$ss['order_customer']['last_name']=$row['customer_name'];
							$ss['order_customer']['address']=NULL;
							$ss['order_customer']['city']=NULL;
							$ss['order_customer']['state']=NULL;
							$ss['order_customer']['country']=NULL;
							$ss['order_customer']['post_code']=NULL;
							$ss['order_customer']['gender']=NULL;
							$ss['order_customer']['dob']=NULL;
							$ss['order_customer']['phone_number']=$row['customer_phone'];
							$ss['order_customer']['email']=$row['customer_email'];
							$ss['order_customer']['is_deleted']="0";
							$ss['order_customer']['customer_id']=$row['cus_id'];
							$ss['order_customer']['customer_type']="1";
							
							$ss['order_hdr_ext']['order_id']=$row['online_order_id'];
							$ss['order_hdr_ext']['tax_invoice_no']="0";
							$ss['order_hdr_ext']['delivery_date']=date('Y-m-d');
							$ss['order_hdr_ext']['delivery_time']=date('H:i:s');
							$ss['order_hdr_ext']['order_mail_receipt']="";
							
							$payments=array();
							$payments['id']=$row['online_order_id'].'-000'.$payment_count;
							$payments['order_id']=$row['online_order_id'];
							$payments['payment_mode']="1";
							$payments['paid_amount']="0.00";
							$payments['card_name']=NULL;
							$payments['card_type']=NULL;
							$payments['card_no']=NULL;
							$payments['name_on_card']=NULL;
							$payments['card_expiry_month']=NULL;
							$payments['card_expiry_year']=NULL;
							$payments['card_approval_code']=NULL;
							$payments['card_account_type']=NULL;
							$payments['pos_customer_receipt']=NULL;
							$payments['pos_merchant_receipt']=NULL;
							$payments['company_id']=NULL;
							$payments['voucher_id']=NULL;
							$payments['voucher_value']=NULL;
							$payments['voucher_count']=NULL;
							$payments['cashier_id']="2";
							$payments['payment_date']=date('Y-m-d H:i:s');
							$payments['payment_time']=date('Y-m-d H:i:s');
							$payments['discount_id']=NULL;
							$payments['discount_code']=NULL;
							$payments['discount_name']=NULL;
							$payments['discount_description']=NULL;
							$payments['discount_price']=NULL;;
							$payments['discount_is_percentage']="false";
							$payments['discount_is_overridable']="false";
							$payments['discount_amount']=NULL;
							$payments['is_repayment']="false";
							$payments['is_voucher_balance_returned']="false";
							$payments['payment_date']=NULL;
							
							$pay[]=$payments;
							$ss['order_payments']=$pay;
							$food_sql="SELECT* from online_order_dtls WHERE order_id='".$row['Id']."' ";
							$f_result =  $conn->query($food_sql);
							if($result->num_rows>0) {
								while($f_row =mysqli_fetch_array($f_result, MYSQLI_ASSOC)) {
									 $fs=array();
									 
									 $sale_sql="SELECT t1.*,t3.code AS uom_code,t3.name AS uom_name,t3.uom_symbol FROM sale_items as t1 LEFT JOIN
									uoms AS t3 ON t3.id=t1.uom_id
									WHERE  t1.code='".$f_row['sale_item_code']."' ";
									$saleitem_one =  $conn->query($sale_sql);
									$saleitem =  $saleitem_one->fetch_assoc();
									
									 $fs['id']=$row['online_order_id'].'-000'.$food_count;
									 $fs['order_id']=$row['online_order_id'];
									 $fs['sale_item_id']="0";
									 $fs['sale_item_code']=$f_row['sale_item_code'];
									 $fs['sub_class_id']=$saleitem['sub_class_id'];
									 $fs['sub_class_code']="0";
									 $fs['sub_class_name']="No Item";
									 $fs['name']=$saleitem['name'];
									 $fs['alternative_name']="";
									 $fs['name_to_print']="";
									 $fs['alternative_name_to_print']="";
									 $fs['qty']=$f_row['sale_qty'];
									 $fs['is_open']="false";
									 $fs['is_combo_item']="false";
									 $fs['is_tax1_applied']="true";
									 $fs['is_tax2_applied']="true";
									 $fs['is_tax3_applied']="false";
									 $fs['is_gst_applied']="false";
									 $fs['is_tax1_included_in_gst']="false";
									 $fs['is_tax2_included_in_gst']="false";
									 $fs['is_tax3_included_in_gst']="false";
									 $fs['is_sc_included_in_gst']="false";
									 $fs['is_sc_applied']="false";
									 $fs['is_printed_to_kitchen']="false";
									 $fs['is_void']="false";
									 $fs['status']=$status;
									 $fs['uom_code']=$saleitem['uom_code'];
									 $fs['uom_name']=$saleitem['uom_name'];
									 $fs['uom_symbol']=$saleitem['uom_symbol'];
									 $fs['fixed_price']=number_format($f_row['item_total'],2);
									 $fs['tax_calculation_method']="0";
									 $fs['tax_id']="1";
									 $fs['tax_code']="NOTAX";
									 $fs['tax_name']="NOTAX";
									 $fs['tax1_name']="NOTAX";
									 $fs['tax1_pc']="0.00";
									 $fs['tax1_amount']=number_format($f_row['item_tax'],2);
									 $fs['tax2_name']="NO TAX";
									 $fs['tax2_pc']="0.00";
									 $fs['tax2_amount']="0.00";
									 $fs['tax3_name']="NO TAX";
									 $fs['tax3_pc']="0.00";
									 $fs['sc_name']=NULL;
									 $fs['sc_pc']=NULL;
									 $fs['sc_amount']=NULL;
									 $fs['gst_name']=NULL;
									 $fs['gst_pc']=NULL;
									 $fs['gst_amount']=NULL;
									 $fs['item_total']=number_format($f_row['item_total'],2);
									 $fs['discount_type']="0";
									 $fs['discount_id']="1";
									 $fs['discount_code']="DF_DSC_NON";
									 $fs['discount_name']="DF_DSC_NON";
									 $fs['discount_price']="0.00";
									 $fs['discount_is_percentage']="false";
									 $fs['discount_is_overridable']="false";
									 $fs['discount_is_item_specific']="false";
									 $fs['discount_is_promotion']="false";
									 $fs['discount_permitted_for']="0";
									 $fs['discount_amount']="0.00";
									 $fs['discount_grouping_quantity']="0";
									 $fs['discount_allow_editing']="0";
									 $fs['round_adjustment']="0.00";
									 $fs['attrib1_name']="";
									 $fs['attrib1_options']="";
									 $fs['attrib2_name']="";
									 $fs['attrib2_options']="";
									 $fs['attrib3_name']="";
									 $fs['attrib3_options']="";
									 $fs['attrib4_name']="";
									 $fs['attrib4_options']="";
									 $fs['attrib5_name']="";
									 $fs['attrib5_options']="";
									 $fs['attrib1_selected_option']=NULL;
									 $fs['attrib2_selected_option']=NULL;
									 $fs['attrib3_selected_option']=NULL;
									 $fs['attrib4_selected_option']=NULL;
									 $fs['attrib5_selected_option']=NULL;
									 $fs['cashier_id']="2";
									 $fs['order_date']=date('Y-m-d');
									 $fs['order_time']=date('Y-m-d H:i:s');
									 $fs['customer_price_variance']="0.00";
									 $fs['discount_variants']="0.00";
									 							 
									 $food[]=$fs;
									 $food_count++;
								}

							}						
					 
							$ss['order_dtls']=$food;							
							$items[]=$ss;
					 
							//$sql3="UPDATE online_order_hrds SET `status`= 'Sent To Shop' WHERE Id = '".$row['Id']."' and shop_code='".$shop_code."' ";
//print_r($sql3);exit();
					//	if ($conn->query($sql3) === TRUE) {}
							$payment_count++;
							
						}
						$items_details['order_hdrs']=$items;
						//$items_details[]=$items;
							$items_details['sync_time']=date('Y-m-d H:i:s');
							$items_details['status']="SUCCESS";
							$items_details['message']="";
					// http_response_code(500); //server error
					 echo json_encode(

					  
					   $items_details
					);
					}
					/****************************************************/
					
				 //}		 
       
			}
}catch(Exception $ex){

       http_response_code(401); //server error
       echo json_encode(array(
         "status" => 401,
         "message" => " Invalid Token"
       ));
     }
	}	 else{

     http_response_code(501); // not found
     echo json_encode(array(
       "status" => 0,
       "message" => 'Invalid Shop'
     ));
   }
}

?>

