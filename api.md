** Route/Link: forgotPin => base-url/forgot-pin
Method => POST
params =>{
          'mobile'  => 'required'
          }
          
          
** Route/Link: inventoryReport => base-url/v1/reports/inventory-report
Method => GET

         
** Route/Link: receiveableDue => base-url/v1/reports/receiveable-due
Method => GET


** Route/Link: payableDue => base-url/v1/reports/payable-due
Method => GET


** Route/Link: fund-transfers => base-url/g-accounts/fund-transfers
Method => POST/GET/PUT/DELETE
params =>{
          'from_account_id'       => 'required',
          'to_account_id'         => 'required|different:from_account_id',
          'amount'                => 'required|numeric',
          'description'           => 'nullable',
          }
          
** Route/Link: fund-transfers => base-url/v1/users
Method => POST/GET/PUT/DELETE
params =>{
          'name'          => 'required',
          'mobile'        => 'required',
          'pin'           => 'required|digits:4',
          'designation'   => 'nullable',
          }
