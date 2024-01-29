public function saveList(Request $request)
    {
        try{
        $validator = new SaveListValidator(resolve('validator'));
        if (!$validator->with($request->all())->passes()) {
            return $this->helpError(2, "Validation Error", $validator->errors());
        } else {
            $request->request->add(['user_id' => Auth::id()]);
            //Creating or updating/editing of list
            $list = $request->id ? Lists::find($request->id)->fill($request->toArray())->update() : Lists::create($request->toArray());
            $listId = $request->id ?:$list->id;
            if($request->email) {
                //Adding or removing contacts from list
                $request->request->add(['list_id' => $listId]);
                $addNewCustomer = new CustomerEntity();
                $result = $request->customer_id ? $addNewCustomer->deleteCustomer($request->customer_id) : $addNewCustomer->addCustomer($request);
                if ($result['_metadata']['outcomeCode'] != 200 && !($request->customer_id)) {
                    $customer = Customer::where('user_id', Auth::id())->where('email', $request->email)->first();
                    $resultNew = $this->customerList->insertCustomerList($customer, $listId);
                    if (is_array($resultNew))
                        return $resultNew;
                }
                //Customers count
                $this::countContactNumbers($listId);
            }
            return $this->helpReturn("List saved successfully");
        }
    }catch (Throwable $exception){
            errorLogs($exception,"saveList");
            return $this->helpError(1, "Something went wrong, please try again.", ["Something went wrong, please try again."]);
        }
    }
