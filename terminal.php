{
		order_hdrs: {
				"shop_id":101,  // kart2123 unique id to identify shop
				“order_date": "2021-08-17",  //yyyy-mm-dd
"order_time": "2021-08-17 11:22:56",  //yyyy-mm-dd hh:mi:ss
"order_total": "100.00",  //Total order value = sum of all order item values
“order_customer”: {
	"customer_id": "60", //from customer list
"phone_number": "9098765644",  //10 digit phone number
},
"order_payments": {
	payment_mode": "1",   //1=”upi , 2=”cash on delivery
"paid_amount": "100.00",  //?
"payment_date": "2021-08-17 11:22:57",   
"payment_time": "2021-08-17 11:22:57", 
"discount_code": null   // how to manage this?
},
"order_dtls": [
	{
		"sale_item_code": "21",//kart123 unique code for product
"name": "Beef Dry Fry",
"qty": "1.00",   
"fixed_price": "100.00",  // is fixed price the price before tax?
"tax": 12  //% percentage
"item_total": "100.00",  // price + tax?
},
	{
		"sale_item_code": "23",//from item list
"name": "Mutton Fry",
"qty": "2.00",
"fixed_price": "160.00",
"tax": 12, //% percentage
"item_total": "320.00",
}
]
}

	}
