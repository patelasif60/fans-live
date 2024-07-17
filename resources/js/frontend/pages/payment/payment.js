import _ from 'lodash';
var currentDevice = null;
var transactionDetail = {};
// transactionDetail.user_id = 8;
// var product = [{"product_id":1,"product_options":[],"quantity":1}];
// var transactionDetail = '{"detail":{"products":' + JSON.stringify(product) +',"selected_collection_time":"as_soon_as_possible","type":"food_and_drink"},"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwNzQyNjk3OCwiZXhwIjoxNjA3NTEzMzc4LCJuYmYiOjE2MDc0MjY5NzgsImp0aSI6ImpiQUpCVUVxUVRSUWtreTEiLCJzdWIiOjgsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.Ig6cRrCs6TDykwVQ7AuTlUMV1aG2LpvytDm2hufvZmI","type":"product"}';
// transactionDetail = JSON.parse(transactionDetail);
// console.log('transactionDetail', transactionDetail);


// let data = "{'detail':{'products':[{'product_id':1,'product_options':[],'quantity':1}],'selected_collection_time':'as_soon_as_possible','type':'food_and_drink'},'token':'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwNzQzMDEwMywiZXhwIjoxNjA3NTE2NTAzLCJuYmYiOjE2MDc0MzAxMDMsImp0aSI6IkdNYlBhb0s0bEo0TkkwWkoiLCJzdWIiOjgsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.Nj5qVLBts_tJj1ssF225_7kSIp71rYAzdABboP8kiXA','type':'product'}";
// data = data.replace(/'/g, '"');
// data = JSON.parse(data);
// // console.log(data);
// console.log(JSON.parse(data));

// Product
// transactionDetail = '{"detail":{"type": "food_and_drink","selected_collection_time": "as_soon_as_possible","products": [{"quantity":1,"product_options":[],"product_id":5,"special_offer_id":9}],"final_amount": "49.50"},"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwOTIzNDY5OCwiZXhwIjoxNjA5MzIxMDk4LCJuYmYiOjE2MDkyMzQ2OTgsImp0aSI6IlJBeThJdzJhZWswVXJPeVQiLCJzdWIiOjgsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.kzG8ixlIKexfDAFYwGCS86flN9zsD80Wl0MRXa8ny2Q","user_id":"8"}';
// transactionDetail = JSON.parse(transactionDetail);

transactionDetail = '{"detail":{"final_amount":"16.00","products":[{"product_id":18,"product_options":[],"quantity":1,"special_offer_id":null}],"selected_collection_time":"as_soon_as_possible","type":"merchandise"},"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYxMzA0NzMyOCwiZXhwIjoxNjEzMTMzNzI4LCJuYmYiOjE2MTMwNDczMjgsImp0aSI6ImhpWHVSaHp2a1gwRFJmb2giLCJzdWIiOjI2MSwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.tetm8FpWdaaiZGttD3mhAQBiKxa1fCGFqGQPK4Acl1s","user_id":"261"}';
transactionDetail = JSON.parse(transactionDetail);

// transactionDetail = "{\"detail\":{\"final_amount\":\"56.10\",\"type\":\"food_and_drink\",\"selected_collection_time\":\"as_soon_as_possible\",\"products\":[{\"product_id\":1,\"product_options\":[{\"id\":6,\"name\":\"\",\"final_additional_cost\":0,\"product_id\":0,\"selected\":false}],\"quantity\":1}]},\"token\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwODEwOTI2MSwiZXhwIjoxNjA4MTk1NjYxLCJuYmYiOjE2MDgxMDkyNjEsImp0aSI6IkZxSVBLVjNrMDZUU0F5a1MiLCJzdWIiOjgsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.bzg8fxBWjfga-HSp-Phv6HFv_Jv2LAXFplzmfJ4P630\",\"user_id\":8}";
// transactionDetail = JSON.parse(transactionDetail);

// console.log('transactionDetail', transactionDetail);

// Ticket
// var transactionDetail = '{"detail":{"match_id":501,"number_of_seats":2,"tickets":[{"pricing_band_id":6,"stadium_block_seat_id":4623},{"pricing_band_id":8,"stadium_block_seat_id":4624}],"final_amount":"242.0"},"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwOTI0MjI5OSwiZXhwIjoxNjA5MzI4Njk5LCJuYmYiOjE2MDkyNDIyOTksImp0aSI6IjRFZTFzWlNOU1ZVZUI1aVUiLCJzdWIiOjExNywicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.93UNO3yLmRLwDxd9iqoM3kzmy2QBoO7Qy-GoaqYZxzQ","user_id":"117"}';
// transactionDetail = JSON.parse(transactionDetail);

// transactionDetail = '{"user_id":259,"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2ZhbnNsaXZlLmFlY29yZGlnaXRhbHFhLmNvbS9hcGkvY29uc3VtZXIvbG9naW4iLCJpYXQiOjE2MTAwMjcyMDEsImV4cCI6MTYxMDExMzYwMSwibmJmIjoxNjEwMDI3MjAxLCJqdGkiOiJaYllvSHYwWE4xREdweklxIiwic3ViIjoyNTksInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gc7FwP2kcNOYpHB_fIBdqZWyovuQA-atOHJpfS9ejog","detail":{"final_amount":"55.00","match_id":222,"number_of_seats":1,"tickets":[{"pricing_band_id":16,"stadium_block_seat_id":6574}]}}';
// transactionDetail = JSON.parse(transactionDetail);

// transactionDetail = '{"detail":{"final_amount":"20.00","match_id":104,"number_of_seats":1,"tickets":[{"pricing_band_id":1,"stadium_block_seat_id":452}]},"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwODcwNTA1NCwiZXhwIjoxNjA4NzkxNDU0LCJuYmYiOjE2MDg3MDUwNTQsImp0aSI6Inp5Q1Q2T29PUlFkVk9yQXYiLCJzdWIiOjgsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.EZZQu-wytJCEWpnht3vSGe-lpPljwFsH-LZI9kvG4IU","user_id":"8"}';
// transactionDetail = JSON.parse(transactionDetail);

// transactionDetail = '{"detail":{"final_amount":"20.00","match_id":104,"number_of_seats":1,"tickets":[{"pricing_band_id":1,"stadium_block_seat_id":1387}]},"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwODcxNTIyMywiZXhwIjoxNjA4ODAxNjIzLCJuYmYiOjE2MDg3MTUyMjMsImp0aSI6Ik8waFFzTnZUYURzSXd6THUiLCJzdWIiOjgsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.ArC-OwKIyPUA4kSuvMtPh-pp0jvRTAzvms679yenIJ8","user_id":"8"}';
// transactionDetail = JSON.parse(transactionDetail);

// transactionDetail = '{"detail":{"final_amount":"220.00","match_id":501,"number_of_seats":2,"tickets":[{"pricing_band_id":4},{"pricing_band_id":4}]},"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwODc5MjM0NCwiZXhwIjoxNjA4ODc4NzQ0LCJuYmYiOjE2MDg3OTIzNDQsImp0aSI6Ijczc0luMXhQa2dYY3d5SmsiLCJzdWIiOjcsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.8j4P76qR5RA20bWjSU-NIb6Qi3_Daiu9A8DK8vxDqD0","user_id":"7"}';
// transactionDetail = JSON.parse(transactionDetail);

// transactionDetail = '{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwODgxNDc1MCwiZXhwIjoxNjA4OTAxMTUwLCJuYmYiOjE2MDg4MTQ3NTAsImp0aSI6IkZhSWVkQ29GemxwdnYwYm8iLCJzdWIiOjcsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.aau7dETyWZHcUkXKzJYae3ORatawBTkOj7tuNFhF-1g","detail":{"final_amount":"55.00","number_of_seats":1,"match_id":500,"tickets":[{"pricing_band_id":5}]},"user_id":7}';
// transactionDetail = JSON.parse(transactionDetail);

// Membership
// var transactionDetail = '{"detail":{"final_amount":"47.88","membership_package_id":3},"final_amount":"47.88","token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwNzY4NDA3MiwiZXhwIjoxNjA3NzcwNDcyLCJuYmYiOjE2MDc2ODQwNzIsImp0aSI6ImRjRnN6UFlZQVFtRUFQbloiLCJzdWIiOjgsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.rq2aNms10mQjUFwVVwqyeeiYK_E-Yi-NjB0gRoJ721U","user_id":"8"}';
// transactionDetail = JSON.parse(transactionDetail);

// Event
// var transactionDetail =  '{"detail":{"event_id":1,"final_amount":"20.20","number_of_seats":2},"final_amount":"20.2","token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwODAxNzQxNSwiZXhwIjoxNjA4MTAzODE1LCJuYmYiOjE2MDgwMTc0MTUsImp0aSI6Ilh2RVFzOHhqZzJJTlZ5WDMiLCJzdWIiOjgsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.7Gu2TNk1nTGRSiKjoPVM7XYrtJAIqwZrz6Qjdh_kCsg","user_id":"8"}';
// transactionDetail = JSON.parse(transactionDetail);

// Hospitality
// var transactionDetail = '{"detail":{"final_amount":"1100.00","hospitality_suite_dietary_options":[{"hospitality_dietary_option_id":1,"selected_quantity":2},{"hospitality_dietary_option_id":2,"selected_quantity":1}],"hospitality_suits_id":1,"match_id":104,"number_of_seats":2},"final_amount":"1100.0","token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwODAxNzQxNSwiZXhwIjoxNjA4MTAzODE1LCJuYmYiOjE2MDgwMTc0MTUsImp0aSI6Ilh2RVFzOHhqZzJJTlZ5WDMiLCJzdWIiOjgsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.7Gu2TNk1nTGRSiKjoPVM7XYrtJAIqwZrz6Qjdh_kCsg","user_id":"8"}'
// transactionDetail = JSON.parse(transactionDetail);

// transactionDetail = '{"user_id":8,"detail":{"match_id":104,"hospitality_suits_id":1,"number_of_seats":1,"final_amount":"550.00"},"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwODE5NzYyOCwiZXhwIjoxNjA4Mjg0MDI4LCJuYmYiOjE2MDgxOTc2MjgsImp0aSI6IkhrUnhremVSRURXb0FLS0giLCJzdWIiOjgsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.dgZdjO5_UvILXejjuAFyexOelNCYM-34dkldhsofE_A"}';
// transactionDetail = JSON.parse(transactionDetail);

// transactionDetail = '{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwODI3OTg4MCwiZXhwIjoxNjA4MzY2MjgwLCJuYmYiOjE2MDgyNzk4ODAsImp0aSI6IjhsdlI4TEVTU2pvM1NjT3ciLCJzdWIiOjgsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.li560zPO7JlLLOyMX4MfftYJw0ki2CScOVqwFHG47ZE","user_id":8,"detail":{"number_of_seats":1,"match_id":104,"hospitality_suits_id":1,"final_amount":"550.00","hospitality_suite_dietary_options":[{"hospitality_dietary_option_id":1,"selected_quantity":0},{"selected_quantity":0,"hospitality_dietary_option_id":2}]}}';
// transactionDetail = JSON.parse(transactionDetail);

// transactionDetail = '{"detail":{"hospitality_suite_dietary_options":[{"hospitality_dietary_option_id":1,"selected_quantity":1},{"hospitality_dietary_option_id":2,"selected_quantity":1}],"hospitality_suits_id":1,"match_id":104,"number_of_seats":1,"final_amount":"550.0"},"final_amount":"550.0","token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwODU1MTAwNSwiZXhwIjoxNjA4NjM3NDA1LCJuYmYiOjE2MDg1NTEwMDUsImp0aSI6ImJMTVBpdXZSZlFvMUFQMTMiLCJzdWIiOjgsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.W5u30Nk4ErtnOZTAeuZzVQJAm5HXI24H-uULmm-peac","user_id":"8"}';
// transactionDetail = JSON.parse(transactionDetail);
// console.log(transactionDetail);

// transactionDetail = '{"detail":{"final_amount":"110.00","hospitality_suite_dietary_options":[],"hospitality_suits_id":5,"match_id":500,"number_of_seats":1},"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbXVrZXNoLWZhbnNsaXZlLmRldi5hZWNvcnRlY2guY29tL2FwaS9jb25zdW1lci9sb2dpbiIsImlhdCI6MTYwOTE1ODkxNywiZXhwIjoxNjA5MjQ1MzE3LCJuYmYiOjE2MDkxNTg5MTcsImp0aSI6InU5RERmVUhwdmpuTXNzNGMiLCJzdWIiOjcsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.WBoTmkOWIYHSF0m3ojYDXDgAibiqq-jw6I7oCw9T6y0","user_id":"7"}';
// transactionDetail = JSON.parse(transactionDetail);

var token = null;
$( document ).ready(function() {
	var userAgent = window.navigator.userAgent.toLowerCase(),
    safari = /safari/.test(userAgent),
    ios = /iphone|ipod|ipad/.test(userAgent);

	if(ios) {
	    if(safari) {
	    } else if ( !safari ) {
	    	currentDevice = 'ios';
	    };
	} else {
		if (userAgent.includes('wv')) {
			currentDevice = 'android';
		} else {
		}
	};
	getTransactionDetail(transactionDetail);
});
var initializeCashierInstance = function() {
	var CashierInstance = new _PaymentIQCashier('#cashier', {
		merchantId: Site.merchantId,
		userId: transactionDetail.user_id,
		sessionId: 1234,
		environment: Site.environment,
		fetchConfig: false,
		accountDelete: false,
		singlePageFlow: true,
		attributes: authoriseRequest(),
		mode: 'ecommerce',
		showTransactionOverview: false,
		predefinedValues: false,
		showCookiesWarning: false,
		zebraffeLoader: false,
		customLogoFileName: "faviconspinner.png",
		theme: {
			loader: {
				color: Site.club_secondary_colour
			},
			buttons: {
				color: Site.club_secondary_colour,
			},
			labels: {
				color: Site.club_primary_colour,
				fontSize: '14px'
			},
			input: {
		        color: Site.club_primary_colour
		    },
		}
    },
    (api) => {
        api.on({
			cashierInitLoad: () => console.log('Cashier init load'),
			update: data => console.log('The passed in data was set', data),
			success: data => makePayment(data),
			failure: data => makePayment(data),
			pending: data => makePayment(data),
			unresolved: data => makePayment(data),
			isLoading: data => console.log('Data is loading', data),
			doneLoading: data => console.log('Data has been successfully downloaded', data),
			newProviderWindow: data => console.log('A new window / iframe has opened', data),
			paymentMethodSelect: data => console.log('Payment method was selected', data),
			paymentMethodPageEntered: data => console.log('New payment method page was opened', data),
			navigate: data => console.log('Path navigation triggered', data)
        },
        )
        api.set({
			config: {
				amount: transactionDetail.detail.final_amount
			}
        })
        api.css(`
        	* {
	          font-family: 'Rubik', sans-serif;
	    	}
	    	.dropdown-container {
	    		color: ${Site.club_primary_colour}
	    	}
			.ecommerce-amount {
				color: ${Site.club_primary_colour}
			}
			.input-label span, .new-account span, .new-account ion-icon {
				color: #D0D0D0
			}
			#cashier .receipt h4 {
				color: ${Site.club_primary_colour}
			}
        `)
      }
    )
}
var authoriseRequest = function()
{
	if(Site.type == 'product') {
		let productIds = _.map(transactionDetail.detail.products, 'product_id');
		//return {"type": 'aa', "product_id":'1,2', "payment_type":Site.type};
	    return {"type": transactionDetail.detail.type, "product_id":productIds, "payment_type":Site.type};
	} else if(Site.type == 'match') {
		let pricingBandIds = _.map(transactionDetail.detail.tickets, 'pricing_band_id');
	    return {"match_id": transactionDetail.detail.match_id, "number_of_seats":transactionDetail.detail.number_of_seats, "pricing_band_id":pricingBandIds, "payment_type": Site.type};
	} else if(Site.type == 'event') {
	    return {"event_id": transactionDetail.detail.event_id, "number_of_seats":transactionDetail.detail.number_of_seats, "payment_type":Site.type};
	} else if(Site.type == 'hospitality') {
	    return {"hospitality_suits_id": transactionDetail.detail.hospitality_suits_id, "number_of_seats":transactionDetail.detail.number_of_seats, "match_id":transactionDetail.detail.match_id, "payment_type":Site.type};
	} else {
	    return {"membership_package_id": transactionDetail.detail.membership_package_id, "payment_type":Site.type};
	}
}
var makePayment = function (transactionSummary) {
	console.log('transactionSummary', transactionSummary);
	// return false;
	transactionSummary.data.status = transactionSummary.data.status.toLowerCase();
	if(Site.type == 'product') {
		makeProductPayment(transactionSummary);
	} else if(Site.type == 'match') {
		makeMatchPayment(transactionSummary);
	} else if(Site.type == 'event') {
		makeEventPayment(transactionSummary);
	} else if(Site.type == 'hospitality') {
		makeHospitalityPayment(transactionSummary);
	} else {
		makeMembershipPayment(transactionSummary);
	}
}

var makeProductPayment = function(transactionSummary) {
	let param = transactionDetail.detail;
	param.products = JSON.stringify(param.products);
	param.transaction_summary = transactionSummary;
	ajaxCall(Site.url + "/api/make_product_payment", param, 'POST', 'json', makeProductPaymentSuccess, 'Bearer' + transactionDetail.token);
	sendTransactionStatus(transactionSummary);
}
var makeProductPaymentSuccess = function (data)
{
	sendTransactionReceipt(data);
}
var makeMatchPayment = function(transactionSummary){
	let param = transactionDetail.detail;
	param.tickets = JSON.stringify(param.tickets);
	param.transaction_summary = transactionSummary;
	ajaxCall(Site.url + "/api/make_match_ticket_payment", param, 'POST', 'json', makeMatchPaymentSuccess, 'Bearer' + transactionDetail.token);
	sendTransactionStatus(transactionSummary);
}
var makeMatchPaymentSuccess = function (data)
{
	sendTransactionReceipt(data);
}
var makeEventPayment = function(transactionSummary){
	let param = transactionDetail.detail;
	param.transaction_summary = transactionSummary;
	ajaxCall(Site.url + "/api/make_event_ticket_payment", param, 'POST', 'json', makeEventPaymentSuccess, 'Bearer' + transactionDetail.token);
	sendTransactionStatus(transactionSummary);
}
var makeEventPaymentSuccess = function (data)
{
	sendTransactionReceipt(data);
}
var makeHospitalityPayment = function(transactionSummary){
	let param = transactionDetail.detail;
	param.hospitality_suite_dietary_options = JSON.stringify(param.hospitality_suite_dietary_options);
	param.transaction_summary = transactionSummary;
	ajaxCall(Site.url + "/api/make_hospitality_ticket_payment", param, 'POST', 'json', makeHospitalityPaymentSuccess, 'Bearer' + transactionDetail.token);
	sendTransactionStatus(transactionSummary);
}
var makeHospitalityPaymentSuccess = function (data)
{
	console.log('data', data);
	sendTransactionReceipt(data);
}
var makeMembershipPayment = function(transactionSummary){
	let param = transactionDetail.detail;
	param.transaction_summary = transactionSummary;
	ajaxCall(Site.url + "/api/make_membership_package_payment", param, 'POST', 'json', makeMembershipPaymentSuccess, 'Bearer' + transactionDetail.token);
	sendTransactionStatus(transactionSummary);
}
var makeMembershipPaymentSuccess = function (data)
{
	sendTransactionReceipt(data);
}
var sendTransactionStatus = function(transactionSummary) {
	let data = new Object();
	data.is_success = transactionSummary.data.status === 'successful' ? 1 : 0;
	data.error_message = data.is_success === 0 ? 'Error' : '';
	if(currentDevice == 'android') {
		Payment.sendTransactionStatus(JSON.stringify(data));
	}
	if(currentDevice == 'ios') {
		window.webkit.messageHandlers.sendTransactionStatus.postMessage(JSON.stringify(data));
	}
}
var sendTransactionReceipt = function(data) {
	if(currentDevice == 'android') {
		Payment.sendTransactionReceipt(JSON.stringify(data));
	}
	if(currentDevice == 'ios') {
		window.webkit.messageHandlers.sendTransactionReceipt.postMessage(JSON.stringify(data));
	}
}
window.getTransactionDetail = function(data) {
	if(currentDevice == 'android') {
		transactionDetail = JSON.parse(data.replace(/'/g, '"'));
	}
	if(currentDevice == 'ios') {
		transactionDetail = JSON.parse(data);
	}
	initializeCashierInstance();
}

