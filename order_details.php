<?php
ini_set("display_errors", 1);

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
					WHERE t1.order_date>='".$created_at."' AND t1.shop_code='".$shop_code."'";
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
						$items=array();
						while($row =mysqli_fetch_array($result, MYSQLI_ASSOC)) {
							$ss=array();
							$food=array();
							// $ss['id']=$row['id'];
							$ss['order_id']=$row['order_id'];
							$ss['order_no']=$row['Id'];
							$ss['shop_code']=$row['shop_code'];
							$ss['station_code']=null;
							$ss['user_id']=null;
							
							$ss['order_date']=$row['order_date'];
							$ss['order_time']=$row['order_date'];
							$ss['shift_id']=0;
							$ss['customer_id']=null;
							$ss['is_ar_customer']=null;
							$ss['detail_total']=null;
							$ss['total_tax1']=null;
							$ss['total_tax2']=null;
							$ss['total_tax3']=null;
							$ss['total_gst']=null;
							$ss['total_sc']=null;
							$ss['total_detail_discount']=$row['total_discount'];
							$ss['final_round_amount']=null;
							$ss['total_amount']=$row['total_amount'];
							$ss['total_amount_paid']=null;
							$ss['total_balance']=null;
							$ss['actual_balance_paid']=null;
							$ss['cash_out']=null;
							$ss['remarks']=$row['remarks'];
							$ss['closing_date']=null;
							$ss['closing_time']=null;
							$ss['status']=$row['status'];
							$ss['total_print_count']=0;
							$ss['refund_total_tax1']=null;
							$ss['refund_total_tax2']=null;
							$ss['refund_total_tax3']=null;
							$ss['refund_total_gst']=null;
							$ss['refund_total_sc']=null;
							$ss['refund_amount']=null;
							$ss['sync_message']=null;
							$ss['created_by']=null;
							$ss['created_at']=null;
							$ss['updated_by']=null;
							$ss['updated_at']=null;
							$ss['sync_status']=null;
							$ss['bill_less_tax_amount']=null;
							$ss['bill_discount_amount']=null;
							$ss['serving_table_id']=null;
							$ss['served_by']=null;
							$ss['service_type']=null;
							$ss['covers']=null;
							
							
							//$ss['order_hdrs']['total_amount']=$row['total_amount'];
							//$ss['order_hdrs']['total_discount']=$row['total_discount'];
							//$ss['order_hdrs']['total_tax']=$row['total_tax'];
							//$ss['order_hdrs']['remarks']=$row['remarks'];
							//$ss['order_hdrs']['status']=$row['status'];

							$ss['order_customer']['order_id']=$row['order_id'];
							$ss['order_customer']['title']=null;
							$ss['order_customer']['first_name']=$row['customer_name'];
							$ss['order_customer']['last_name']=$row['customer_name'];
							$ss['order_customer']['address']=null;
							$ss['order_customer']['city']=null;
							$ss['order_customer']['state']=null;
							$ss['order_customer']['country']=null;
							$ss['order_customer']['post_code']=null;
							$ss['order_customer']['gender']=null;
							$ss['order_customer']['dob']=null;
							$ss['order_customer']['phone_number']=$row['customer_phone'];
							$ss['order_customer']['email']=$row['customer_email'];
							$ss['order_customer']['is_deleted']=0;
							$ss['order_customer']['customer_id']=$row['cus_id'];
							$ss['order_customer']['customer_type']=0;
							
							$ss['order_hdr_ext']['order_id']=$row['order_id'];
							$ss['order_hdr_ext']['tax_invoice_no']=null;
							$ss['order_hdr_ext']['delivery_date']=null;
							$ss['order_hdr_ext']['delivery_time']=null;
							$ss['order_hdr_ext']['order_mail_receipt']="";
							
							$ss['order_payments']['id']="";
							$ss['order_payments']['order_id']=$row['order_id'];
							$ss['order_payments']['payment_mode']=0;
							$ss['order_payments']['paid_amount']=0;
							$ss['order_payments']['card_name']=null;
							$ss['order_payments']['card_type']=null;
							$ss['order_payments']['card_no']=null;
							$ss['order_payments']['name_on_card']=null;
							$ss['order_payments']['card_expiry_month']=null;
							$ss['order_payments']['card_expiry_year']=null;
							$ss['order_payments']['card_approval_code']=null;
							$ss['order_payments']['card_account_type']=null;
							$ss['order_payments']['pos_customer_receipt']=null;
							$ss['order_payments']['pos_merchant_receipt']=null;
							$ss['order_payments']['company_id']=null;
							$ss['order_payments']['voucher_id']=null;
							$ss['order_payments']['voucher_value']=null;
							$ss['order_payments']['voucher_count']=null;
							$ss['order_payments']['cashier_id']="";
							$ss['order_payments']['payment_date']="";
							$ss['order_payments']['payment_time']="";
							$ss['order_payments']['discount_id']=null;
							$ss['order_payments']['discount_code']=null;
							$ss['order_payments']['discount_name']=null;
							$ss['order_payments']['discount_description']=null;
							$ss['order_payments']['discount_price']=null;;
							$ss['order_payments']['discount_is_percentage']="";
							$ss['order_payments']['discount_is_overridable']="";
							$ss['order_payments']['discount_amount']=null;
							$ss['order_payments']['is_repayment']="";
							$ss['order_payments']['is_voucher_balance_returned']="";
							$ss['order_payments']['payment_date']=null;
							
							
							$food_sql="SELECT* from online_order_dtls WHERE order_id='".$row['Id']."' ";
							$f_result =  $conn->query($food_sql);
							if($result->num_rows>0) {
								while($f_row =mysqli_fetch_array($f_result, MYSQLI_ASSOC)) {
									 $fs=array();
									 $fs['id']=$f_row['id'];
									 $fs['order_id']=$f_row['order_id'];
									 $fs['sale_item_id']=null;
									 $fs['sale_item_code']=$f_row['sale_item_code'];
									 $fs['sub_class_id']=0;
									 $fs['sub_class_code']=0;
									 $fs['sub_class_name']="";
									 $fs['name']="";
									 $fs['alternative_name']="";
									 $fs['name_to_print']="";
									 $fs['alternative_name_to_print']="";
									 $fs['qty']=$f_row['sale_qty'];
									 $fs['is_open']=null;
									 $fs['is_combo_item']=null;
									 $fs['is_tax1_applied']=null;
									 $fs['is_tax2_applied']=null;
									 $fs['is_tax3_applied']=null;
									 $fs['is_gst_applied']=null;
									 $fs['is_tax1_included_in_gst']=null;
									 $fs['is_tax2_included_in_gst']=null;
									 $fs['is_tax3_included_in_gst']=null;
									 $fs['is_sc_included_in_gst']=null;
									 $fs['is_sc_applied']=null;
									 $fs['is_printed_to_kitchen']=null;
									 $fs['is_void']=null;
									 $fs['status']=null;
									 $fs['uom_code']=null;
									 $fs['uom_name']=null;
									 $fs['uom_symbol']=null;
									 $fs['fixed_price']=$f_row['item_total'];
									 $fs['tax_calculation_method']=null;
									 $fs['tax_id']=null;
									 $fs['tax_code']=null;
									 $fs['tax_name']=null;
									 $fs['tax1_name']=null;
									 $fs['tax1_pc']=null;
									 $fs['tax1_amount']=$f_row['item_tax'];
									 $fs['tax2_name']=null;
									 $fs['tax2_pc']=null;
									 $fs['tax2_amount']=null;
									 $fs['tax3_name']=null;
									 $fs['tax3_pc']=null;
									 $fs['sc_name']=null;
									 $fs['sc_pc']=null;
									 $fs['sc_amount']=null;
									 $fs['gst_name']=null;
									 $fs['gst_pc']=null;
									 $fs['gst_amount']=null;
									 $fs['item_total']=$f_row['item_total'];
									 $fs['discount_type']=null;
									 $fs['discount_id']=0;
									 $fs['discount_code']=0;
									 $fs['discount_name']=null;
									 $fs['discount_price']=0;
									 $fs['discount_is_percentage']=0;
									 $fs['discount_is_overridable']=0;
									 $fs['discount_is_item_specific']=0;
									 $fs['discount_is_promotion']=null;
									 $fs['discount_permitted_for']=null;
									 $fs['discount_amount']=0;
									 $fs['discount_grouping_quantity']=0;
									 $fs['discount_allow_editing']=0;
									 $fs['round_adjustment']=0;
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
									 $fs['attrib1_selected_option']=null;
									 $fs['attrib2_selected_option']=null;
									 $fs['attrib3_selected_option']=null;
									 $fs['attrib4_selected_option']=null;
									 $fs['attrib5_selected_option']=null;
									 $fs['cashier_id']=0;
									 $fs['order_date']="";
									 $fs['order_time']="";
									 $fs['customer_price_variance']="";
									 $fs['discount_variants']="";
									 							 
									 $food[]=$fs;
							 
								}

							}						
					 
							$ss['order_dtls']=$food;							
							$items[]=$ss;
					 
						}
						$items_details['order_hdrs']=$items;
							$items_details['sync_time']=date('Y-m-d H:i:s');
							$items_details['status']="SUCCESS";
							$items_details['message']="";
					// http_response_code(500); //server error
					 echo json_encode(array(

					   "status" => 200,
					   "jsonConfig" => $items_details
					 ));
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

     http_response_code(404); // not found
     echo json_encode(array(
       "status" => 0,
       "message" => 'Invalid Shop'
     ));
   }
}

?>