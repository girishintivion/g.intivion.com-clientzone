<?php
require_once(APPPATH.'libraries/requestparam.php');
class Real_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        public function get_trading_experience($username)
        {
        	$this->db->select('trading_experience');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('trading_experience');
        
        }
        
        public function get_countries()
        { 	
        	$query = $this->db->get('countries_country');
        	return $query->result_array();
        }
        
        
        public function insert_into_crm_account($Employment_status,$Employment_type,$Education_level,$trading_experience,$us_reportable,$us_citizen,$us_birth,$current_US_mailing,$US_telephone,$instructions_to_transfer_funds,$power_of_attorney,$in_care_of,$us_citizen_value,$us_birth_value,$current_US_mailing_value,$US_telephone_value,$instructions_to_transfer_funds_value,$power_of_attorney_value,$in_care_of_value)
        {
       
        
        	$userinfo = array(
        			'tp_name'  => $_SESSION['username'],
        			'employment_status' => $Employment_status,
        			'employment_type'      => $Employment_type,
        			'education_level'   => $Education_level,
        			'previous_experience' => $trading_experience,
        			'us_reportable'  => $us_reportable,
        			'us_citizen'  => $us_citizen,
        			'us_birth_place'  => $us_birth,
        			'current_us_address'  => $current_US_mailing,
        			
        			'us_telephone'   => $US_telephone,
        			'transfer_fund_us' => $instructions_to_transfer_funds,
        			'power_of_attorney'  => $power_of_attorney,
        			'in_care_of'  => $in_care_of,
        			

        			'us_citizen_value'  => $us_citizen_value,
        			'us_birth_value'  => $us_birth_value,
        			'current_US_mailing_value'  => $current_US_mailing_value,
        			 
        			'US_telephone_value'   => $US_telephone_value,
        			'instructions_to_transfer_funds_value' => $instructions_to_transfer_funds_value,
        			'power_of_attorney_value'  => $power_of_attorney_value,
        			'in_care_of_value'  => $in_care_of_value,

        			 
        	);
        	$this->db->insert('crm_account', $userinfo);
        
        }
        
        
        public function update_crm_account_step4($annual_income,$net_worth,$wealth_source,$annual_deposit)
        {
        
        
        	$userinfo = array(
        			'annual_income'   => $annual_income,
        			'net_worth'      => $net_worth,
        			'wealth_source'   => $wealth_source,
        			'annual_deposit' => $annual_deposit,
	 
        	);
        	 
        	$this->db->where('tp_name', $_SESSION['username']);
        	$this->db->update('crm_account', $userinfo);
        	 
        	 
        
        }
        
        
        public function update_crm_account_final_score($final_score)
        {
        
        
        	$userinfo = array(
        			'final_score'   => $final_score,

        
        	);
        
        	$this->db->where('tp_name', $_SESSION['username']);
        	$this->db->update('crm_account', $userinfo);
        
        
        
        }
        
        
        public function update_crm_account_leverage($leverage)
        {
        
        
        	$userinfo = array(
        			'leverage'   => $leverage,
        
        
        	);
        
        	$this->db->where('tp_name', $_SESSION['username']);
        	$this->db->update('crm_account', $userinfo);
        
        
        
        }
        
        
        public function get_phone_code($country_code)
        {
        	$query = $this->db->get_where('countries_country', array('iso2' => $country_code));
        	return $query->row_array();
        }
       
        
        public function get_password($username)
        {
        	$this->db->select('secret_data');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('secret_data');
        	 
        }
        public function create_user($username,$password,$platformtype)
        {
        	if($platformtype=="MT4")
        	{
        		$user_role="Mt4";
        	}
        	else
        	{
        	 	$user_role="REAL";
        	}
        	
        	$userinfo = array(
        			'username'   => $username,
        			'email'      => $this->input->post('email'),
        			'password'   => $this->hash_password($password),
        			'created_at' => date('Y-m-j H:i:s'),
        			'user_role'  => $user_role,
        	);
        	$this->db->insert('users_ci', $userinfo);
        
        }
        
        
        public function addresscopy_upload($accountId,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME)
        {
        
        	 
        			$userinfo = array(
        			'business_unit'   => $BUSINESS_UNIT_NAME,
        			'file_url'      => $filecontents,
        			'status'   => "0",
        			'uploaded_date' => date('Y-m-j H:i:s'),
        			'doc_type'  => "Address Copy",
        			'account_id'  => $accountId,
        			'original_filename'  => $original_name,
        			'file_name'  => $modi_name,
        					
        	);
        	$this->db->insert('traders_files', $userinfo);
        
        }
        
        
        public function update_create_jointaccount($tradingPlatformAccountName,$tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$currency_id,$currency_code,$accountId,$tradingPlatformAccountId,$uniquenum,$country_residence,$date,$passportissuedate,$passportexpitrdate)
        {
        
        
        	$userinfo = array(
        			's_firstname'   => $this->input->post('firstname'),
        			's_lastname'      => $this->input->post('lastname'),
        			's_citizen'   => $country_residence,
        			's_pob' => $this->input->post('place_birth'),
        			's_nationality'  => $this->input->post('nationality'),
        			's_dob'  => $date,
        			's_gender'  => $this->input->post('gender'),
        			's_pid'  => $passportissuedate,
        			's_ped'   => $passportexpitrdate,
        			's_pub_pos'      => $this->input->post('publicposition'),
        			's_rel_pub_pos'   => $this->input->post('relatedtopublicposition2'),
        			's_address' => $this->input->post('address2_line1'),
        			's_city'  => $this->input->post('city'),
        			's_country'  => $country_residence,
        			's_zip'  => $this->input->post('zipcode'),
        			's_pco'  => $this->input->post('country_code'),
        			 
        			's_phone'   => $this->input->post('phone'),
        			's_mob' => $this->input->post('mobile'),
        			's_email'  => $this->input->post('email_confirmation'),
        			's_income_source'  => $this->input->post('income_source'),
        			's_gross_income'  => $this->input->post('grossincome'),
        			's_net_worth'  => $this->input->post('net_worth'),
        			 
        			's_pur_of_trans'   => $this->input->post('purpose'),
        			's_trade_size' => $this->input->post('trade_val'),
        			's_acc_turnover'  => $this->input->post('anticipated'),
        			's_trade_past'  => $this->input->post('trade_freq'),
        			's_trade_exp'  => $this->input->post('experience'),
        			's_cfd'  => $this->input->post('commodities'),
        			 
        			's_curriencies'   => $this->input->post('currencies'),
        			's_future' => $this->input->post('futures'),
        			's_title'  => $this->input->post('title'),
        			's_country_residence'  => $country_residence,
        			
        			'business_unit'=>$BUSINESS_UNIT_NAME,
        			'tp_account_id' => $tradingPlatformAccountName,
        			'typeofaccount'=>"2",
        			'account_id' =>$accountId,
        			'status' =>"0",
					's_uscitizen'   => $this->input->post('uscitizen'),
        			's_uscitizen_other' => $this->input->post('uscitizen_other'),
					's_options_exp ' => $this->input->post('options_sec'),
        			's_secu_exp '  => $this->input->post('securities_sec'),
					's_education '  => $this->input->post('education'),
        			
        			
        	);
        	
        	$this->db->where('unique_id', $uniquenum);
        	$this->db->update('joint_account', $userinfo);
        	
        	
        
        }
        
        public function real_step_one_crm_account2($passportissuedate,$passportexpitrdate,$taxresidency,$birth_diff_than_nationality,$usciti)
        {
        
        	$data = array(
        
        			'pid' => $passportissuedate,
        			'ped'=> $passportexpitrdate,
        			'us_citizen'=> $usciti,
        			'us_citizen_other'=> $this->input->post('uscitizen_other'),
        			'tp_account_id'=> $_SESSION['username'],
        			'nationality'=>$this->input->post('countryofnationality'),
        			'birth_diff_than_nationality'=>$birth_diff_than_nationality,
        			'place_of_birth'=>$this->input->post('placeofbirth'),
        			'street'=>$this->input->post('address'),
        			'city'=>$this->input->post('city'),
        			'province'=>$this->input->post('state'),
        			'zip'=>$this->input->post('zipcode'),
        			'tax_diff_than_residency'=>$this->input->post('tax-diff-than-residency'),
        			'tax_residency'=>$taxresidency,
        
        	);
        
        	$this->db->insert('crm_account', $data);
        	return ($this->db->affected_rows() != 1) ? false : true;
        }
        
        
        
        
        public function update_real_step_one_crm_account($passportissuedate,$passportexpitrdate,$taxresidency,$birth_diff_than_nationality,$usciti)
        {
        
        	$data = array(
        
        			//	'place_of_birth' => $this->input->post('placeofbirth'),
        			'pid' => $passportissuedate,
        			'ped'=> $passportexpitrdate,
        			'us_citizen'=> $usciti,
        			'us_citizen_other'=> $this->input->post('uscitizen_other'),
        			'tp_account_id'=> $_SESSION['username'],
        			'nationality'=>$this->input->post('country-of-nationality'),
        			'birth_diff_than_nationality'=>$birth_diff_than_nationality,
        			'place_of_birth'=>$this->input->post('placeofbirth'),
        			'street'=>$this->input->post('address'),
        			'city'=>$this->input->post('city'),
        			'province'=>$this->input->post('state'),
        			'zip'=>$this->input->post('zipcode'),
        			'tax_diff_than_residency'=>$this->input->post('tax-diff-than-residency'),
        			'tax_residency'=>$taxresidency,
        
        	);
        	$this->db->where('tp_account_id', $_SESSION['username']);
        	$this->db->update('crm_account', $data);
        	return ($this->db->affected_rows() != 1) ? false : true;
        
        
        }
        
        
        public function update_create_crmaccount2($accId,$addinfos,$Passed_Appropriateness_Test)
        {
        
        	$data = array(
        			'account_id' => $accId,
        			'incoming_call' => $call,
        			'education'=> $this->input->post('education'),
        			'trading_experience'=> $this->input->post('experience'),
        			'income_source'=> $this->input->post('income_source'),
        			'trading_frequency'=> $this->input->post('trade_freq'),
        			'trading_volume'=> $this->input->post('trade_val'),
        			'purpose_transaction'=> $this->input->post('purpose'),
        			'additional_info'=> $addinfos,
        			'net_worth'=> $this->input->post('net_worth'),
        			'annual_gross_income'=> $this->input->post('income'),
        			'account_type' => "1",
        			'accept_terms'=>"1",
        			'bonus_terms'=> $this->input->post('bonus_terms'),
        			'business_unit'=> $_SESSION['BUSINESS_UNIT'],
        			'tp_account_id'=> $tradingPlatformAccountName,
        			'status'=>"1",
        			'turnoverperyear'=> $this->input->post('anticipatedaccountturnoverperyear'),
					'passed_Appropriateness_Test'=> $Passed_Appropriateness_Test,
        			'other_income_source'=> $this->input->post('income_source_other2'),
        			'other_education'=> $this->input->post('education_other'),
        			'other_intended_purpose'=> $this->input->post('income_source_other')
        			
        
        
        	);
        
        	$this->db->where('tp_account_id', $_SESSION['username']);
        	$this->db->update('crm_account', $data);
        
        
        }
        
        public function create_crmaccount($tradingPlatformAccountName,$BUSINESS_UNIT_NAME,$accountId)
        {
        
        
        	$userinfo = array(
        			'tp_account_id'   => $tradingPlatformAccountName,
        			'business_unit'   => $BUSINESS_UNIT_NAME,
        			'account_id'   => $accountId,
        			//'trading_experience'=> $this->input->post('experience'),
        			//'profession'   => $this->input->post('profession'),
        			//'employment_status'      => $this->input->post('employment_status'),
        			//'organization'   => $this->input->post('organization'),
        			//'politically_exposed' => $this->input->post('politically_exposed'),
        			//'us_citizen'  => $this->input->post('uscitizen'),
        			//'annual_gross_income'  => $this->input->post('annual_income'),
        			//'additional_info'  => $this->input->post('wealth_size'),
        			//'income_source'  => $this->input->post('income_source'),
        			//'expect_fund'   => $this->input->post('expect_fund'),
        			//'withdraw_fund_destination'      => $this->input->post('withdraw_fund_destn'),
        			//'turnoverperyear'  => $this->input->post('anticipated_amount'),
        			//'purpose_transaction'  => $this->input->post('purpose'),
        			//'risk_knowledge_experience'  => $this->input->post('risk_knowledge'),
        			'trading_experience'  => $this->input->post('experience'),
        			
        			//'trading_frequency'   => $this->input->post('avg_annual_freq'),
        			//'non_advised_brokerage' => $this->input->post('non_advised_brokerage'),
        			//'last_transaction'  => $this->input->post('last_trans'),
        			//'leverage_products_additionalinfo'  => $addinfos,
        			
        	);
        	$this->db->insert('crm_account', $userinfo);
        
        }
        
        public function document_upload($accId,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,$status)
        {
        
        
        	$userinfo = array(
        			'business_unit'   => $BUSINESS_UNIT_NAME,
        			'file_url'      => $filecontents,
        			'status'   => $status,
        			'uploaded_date' => date('Y-m-j H:i:s'),
        			'doc_type'  => $doc_type,
        			'account_id'  => $accId,
        			'original_filename'  => $original_name,
        			'file_name'  => $modi_name,
        
        	);
        	$this->db->insert('traders_files', $userinfo);
        
        }
       
        public function create_corporateaccount($tradingPlatformAccountName,$tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$currency_id,$currency_code,$accountId,$tradingPlatformAccountId,$date,$passportissuedate,$countryofincorporation)
        {
        
        
        	$userinfo = array(
        			'c_name'   => $this->input->post('compname'),
        			
						'c_address' => $this->input->post('compaddress'),
						'c_city' => $this->input->post('compcity'),
						'c_country' => $this->input->post('country'),
						'c_zip' => $this->input->post('zipcode'),
						'c_phone' => $this->input->post('compphone'),
						'tax_id' => $this->input->post('taxidnum'),
						'incorporation' => $countryofincorporation,
						'incorporate_date' => $date,
						'giin' => $this->input->post('giin'),
						'lei' => $this->input->post('lei'),
						'irs' =>$this->input->post('lxlite_irschapter3status'),
						'bu_criteria'=>$this->input->post('lxlite_businessstructuremeets'),
						'bankrupcy' => $this->input->post('lxlite_bankrupcyinlast10years'),
						'date_of_discharge' =>$passportissuedate,
						'personal_accets' => $this->input->post('lxlite_personalassetsofatleast500000'),
						'trans_in_last_year'=>$this->input->post('lxlite_significantftransactioninthelastyear'),
						'pos_in_company'=>$this->input->post('positioncomp'),
						'exp_in_service_sector'=>$this->input->post('lxlite_financialexperienceinservicesector'),
						'secu_exp'=>$this->input->post('lxlite_securitiesexperience'),
						'secu_freq_trade'=>$this->input->post('lxlite_securitiesfrequencyoftrades'),
						'options_exp'=>$this->input->post('lxlite_optionsexperience'),
						'options_freq_trades'=>$this->input->post('lxlite_optionsfrequencyoftrades'),
						'commodities_exp'=>$this->input->post('lxlite_commoditiesexperience'),
						'commodities_freq_trade'=>$this->input->post('lxlite_commoditiesfrequencyoftrades'),
						'future_exp'=>$this->input->post('lxlite_futuresexperience'),
						'future_treq_trade'=>$this->input->post('lxlite_futuresfrequencyoftrades'),
						'curr_exp'=>$this->input->post('lxlite_currenciesexperience'),
						'curr_frq_trade'=>$this->input->post('lxlite_currenciesfrequencyoftrades'),
						'cfd_exp'=>$this->input->post('lxlite_cfdsexperience'),
						'cfd_freq_trades'=>$this->input->post('lxlite_cfdsfrequencyoftrades'),
						'spread_bets_exp'=>$this->input->post('lxlite_spreadbetsexperience'),
						'spread_bets_freq_of_trade'=>$this->input->post('lxlite_spreadbetsfrequencyoftrades'),
						'tax_residency'=>$this->input->post('taxresidency'),
						'ustaxcode'=>$this->input->post('uscitizen_other'),
						'uscitizen'=>$this->input->post('uscitizen'),
						'regulated_financial'=>$this->input->post('lxlite_financialauthorityorganization'),
						'significant_size'=>$this->input->post('significant_size'),
						'account_id'=>$accountId,
						'status'=>"0",
						'business_unit'=>$BUSINESS_UNIT_NAME,
						'tp_account_id'=>$tradingPlatformAccountName,
						'typeofaccount'=>"3",
        	);
        	$this->db->insert('corporate_account', $userinfo);
        
        }
        
        
        
        
        public function passportcopy_upload($accountId,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME)
        {
        
        
        	$userinfo = array(
        			'business_unit'   => $BUSINESS_UNIT_NAME,
        			'file_url'      => $filecontents,
        			'status'   => "0",
        			'uploaded_date' => date('Y-m-j H:i:s'),
        			'doc_type'  => "Passport Copy",
        			'account_id'  => $accountId,
        			'original_filename'  => $original_name,
        			'file_name'  => $modi_name,
        	);
        	$this->db->insert('traders_files', $userinfo);
        
        }
        
        
        
        
        
        public function create_users($username,$password,$email)
        {
        	
        	 
        	$userinfo = array(
        			'username'   => $username,
        			'email'      => $email,
        			'password'   => $this->hash_password($password),
        			'created_at' => date('Y-m-j H:i:s'),
        			'user_role'  => "REAL",
        	);
        	$this->db->insert('users_ci', $userinfo);
        
        }
        
        
        
        
        public function create_crmuser($tradingPlatformAccountName,$tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$currency_id,$currency_code,$accountId,$tradingPlatformAccountId,$date,$country,$city,$address,$platform_type,$firstname,$lastname)
        {
        
        	$data = array(
        						//'title' => $this->input->post('title'),
        						//'slug' => $slug,
        						'firstname' => $firstname,
        						'lastname' => $lastname,
        						'country'=> $country,
        						'trading_platform'=> "REAL",
        						'account_type'=> "1",
        						'currency'=> $currency_code,
        						'platform'=> $platform_type,
        						'secret_data'=> $tradingPlatformAccountPassword,
        						'phone_country_code'=> $this->input->post('country_code'),
        						'phone'=> $this->input->post('phone'),
        						'birth_date'=>$date,
        						'email' => $this->input->post('email'),
        						'trading_platform_accountid'=>$tradingPlatformAccountId,
        						'account_id'=>$accountId,
        						'business_unit'=>$BUSINESS_UNIT_NAME,
        						'currency_id'=>$currency_id,
        						'currency_code'=>$currency_code,
        						'name'=>$tradingPlatformAccountName,
								'city'=> $city,
								'address1'=> $address,
							//	'trading_experience'=> $trading_experience,
								
        						 
        				);
        		
        				$this->db->insert('crm_user', $data);
        }
        
        
        
        public function create_crmuser_quick($tradingPlatformAccountName,$tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$currency_id,$currency_code,$accountId,$tradingPlatformAccountId,$date,$country,$city,$address,$trading_experience)
        {
        
        	$data = array(
        			//'title' => $this->input->post('title'),
        			//'slug' => $slug,
        			'firstname' => $this->input->post('first_name'),
        			'lastname' => $this->input->post('first_name'),
        			'country'=> $country,
        			'trading_platform'=> "REAL",
        			'account_type'=> "1",
        			'currency'=> $currency_code,
        			'platform'=> "SIRIX",
        			'secret_data'=> $tradingPlatformAccountPassword,
        			'phone_country_code'=> $this->input->post('country_codee'),
        			'phone'=> $this->input->post('phone'),
        			'birth_date'=>$date,
        			'platform'=>"SIRIX",
        			'email' => $this->input->post('email'),
        			'trading_platform_accountid'=>$tradingPlatformAccountId,
        			'account_id'=>$accountId,
        			'business_unit'=>$BUSINESS_UNIT_NAME,
        			'currency_id'=>$currency_id,
        			'currency_code'=>$currency_code,
        			'name'=>$tradingPlatformAccountName,
        			'city'=> $city,
        			'address1'=> $address,
        			'trading_experience'=> $trading_experience,
        
        			 
        	);
        
        	$this->db->insert('crm_user', $data);
        }
        
        
        
        public function get_alluid_records($uniquenum)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('unique_id', $uniquenum);
        	return $this->db->get()->row();
        
        }
        
        
        public function get_joinacct_records($uniquenum)
        {
        	$this->db->select('*');
        	$this->db->from('joint_account');
        	$this->db->where('unique_id', $uniquenum);
        	return $this->db->get()->row();
        
        }
        
        
        
     
        
        
       
        
        
        
        //for second step in real account
        
        public function real_step_two($uniquenum)
        {
        
        	$data = array(
        			//'title' => $this->input->post('title'),
        			//'slug' => $slug,
        			'zipcode' => $this->input->post('zipcode'),
        			'address1' => $this->input->post('address'),
        			'city'=> $this->input->post('city'),
        			'secret_data'=> $this->input->post('password'),
        			'email'=> $this->input->post('email'),
        			 
        
        	);
        	
        	$this->db->where('unique_id', $uniquenum);
        	$this->db->update('crm_user', $data);
        	return ($this->db->affected_rows() != 1) ? false : true;
        	
        	 
        }
        
        
        
        public function update_create_crmuser($tradingPlatformAccountName,$tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$currency_id,$currency_code,$accountId,$tradingPlatformAccountId,$uniquenum)
        {
        
        	$data = array(
        			'name' => $tradingPlatformAccountName,
        			'secret_data' => $tradingPlatformAccountPassword,
        			'business_unit'=> $BUSINESS_UNIT_NAME,
        			'currency_id'=> $currency_id,
        			'currency_code'=> $currency_code,
        			'trading_platform'=> "REAL",
        			'account_id'=> $accountId,
        			'trading_platform_accountid'=> $tradingPlatformAccountId,
        			
        			
        	);
        	 
        	$this->db->where('unique_id', $uniquenum);
        	$this->db->update('crm_user', $data);
        	 
        
        }
        
        
        
        public function delete_crmusers($uniquenum)
        {
        	$this->db->where('unique_id', $uniquenum);
        	$this->db->delete('crm_user');
        }
        
        public function delete_crmaccount($uniquenum)
        {
        	$this->db->where('unique_id', $uniquenum);
        	$this->db->delete('crm_account');
        }
        
        public function delete_joint($uniquenum)
        {
        	$this->db->where('unique_id', $uniquenum);
        	$this->db->delete('joint_account');
        }
        
          public function update_leverage($account_id,$requested_for_higher_leverage)
        {
        
        	$data = array(
        
        			//	'place_of_birth' => $this->input->post('placeofbirth'),
        			'requested_for_higher_leverage' => $requested_for_higher_leverage,
        			'status' => '0',
        
        	);
        	$this->db->where('account_id', $account_id);
        	$this->db->update('crm_account', $data);
        
        
        }
        
        
        public function update_create_crmaccount($tradingPlatformAccountName,$BUSINESS_UNIT_NAME,$tradingPlatformAccountId,$uniquenum)
        {
        
        	$data = array(
        			'account_id' => $tradingPlatformAccountId,
        			'business_unit'=> $BUSINESS_UNIT_NAME,
        			'tp_account_id'=> $tradingPlatformAccountName,
        			'risk_knowledge_experience'=> $this->input->post('risk_knowledge'),
        			'profession_relevant_financialservice'=> $this->input->post('profession_relevant_financialservice'),
        			'profession_relevant_financialindustry'=> $this->input->post('profession_relevant_financialindustry'),
        			'profession_relevant_both'=> $this->input->post('profession_relevant_both'),
        			'profession_relevant_other'=> $this->input->post('profession_financial_service_other'),
        			'financial_instrument'=> $this->input->post('financial_instrument'),
        			'trading_experience'=> $this->input->post('invest_years'),
        			'trading_frequency'=> $this->input->post('avg_annual_freq'),
        			'non_advised_brokerage'=> $this->input->post('non_advised_brokerage'),
        			'last_transaction'=> $this->input->post('last_trans'),
        			'trading_volume'=> $this->input->post('avg_annual_vol'),
        			'leverage_products_additionalinfo'=> $addinfos,
        			
        					
        			 
        			 
        	);
        
        	$this->db->where('unique_id', $uniquenum);
        	$this->db->update('crm_account', $data);
        
        
        }
        
        
        
        
        
        
        
        public function real_step_two_crm_account($uniquenum)
        {
        
        	$data = array(
     //   			'prefered_language' => $this->input->post('prelanguage'),
    //    			'zip' => $this->input->post('zipcode'),
     //   			'street' => $this->input->post('address'),
    //    			'province' => $this->input->post('state'),
        			'profession' => $this->input->post('profession'),
        			'employment_status' => $this->input->post('employment_status'),
        			'organization' => $this->input->post('organization'),
        			'politically_exposed' => $this->input->post('politically_exposed'),
        			'us_citizen' => $this->input->post('uscitizen'),
        			'annual_gross_income' => $this->input->post('annual_income'),
        			'trade_size' => $this->input->post('wealth_size'),
        			'income_source'=> $this->input->post('income_source'),
        			'expect_fund'=> $this->input->post('expect_fund'),
        			'withdraw_fund_destination'=> $this->input->post('withdraw_fund_destn'),
        			'institution'=> $this->input->post('institution'),
        			'nationality'=> $this->input->post('country_origin'),
        			'annual_anticipated_amount'=> $this->input->post('anticipated_amount'),
        			'purpose_transaction'=> $this->input->post('purpose'),
        	);
        	
        	
        	$this->db->where('unique_id', $uniquenum);
        	$this->db->update('crm_account', $data);
        	return ($this->db->affected_rows() != 1) ? false : true;
        
        }
        
        
        
        
        
		
		
		
		public function create_user_three($username,$password)
        {
        	
        	
        	$userinfo = array(
        			'username'   => $username,
        			'email'      => $this->input->post('confirmation_email'),
        			'password'   => $this->hash_password($password),
        			'created_at' => date('Y-m-j H:i:s'),
        			'user_role'  => $user_role,
        	);
        	$this->db->insert('users_ci', $userinfo);
        	
        	
        
        
        }
        
        public function create_crmuser_three($tradingPlatformAccountName,$tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$currency_id,$currency_code,$accountId,$tradingPlatformAccountId,$date,$country)
        {
        
        	$data = array(
        						'title' => $this->input->post('title'),
        						//'slug' => $slug,
        						'firstname' => $this->input->post('firstname'),
        						'lastname' => $this->input->post('lastname'),
        						'country'=> $country,
        						'trading_platform'=> "REAL",
        						'account_type'=> $this->input->post('acctype'),
        						'currency'=> $currency_code,
        						'platform'=> "sirix",
        						'secret_data'=> $tradingPlatformAccountPassword,
        						'phone_country_code'=> $this->input->post('country_code'),
        						'phone'=> $this->input->post('phone'),
        						'birth_date'=>$date,
        					//	'platform'=>$this->input->post('platform'),
        						'email' => $this->input->post('confirmation_email'),
        						'trading_platform_accountid'=>$tradingPlatformAccountId,
        						'account_id'=>$accountId,
        						'business_unit'=>$BUSINESS_UNIT_NAME,
        						'currency_id'=>$currency_id,
        						'currency_code'=>$currency_code,
        						'name'=>$tradingPlatformAccountName,
        						 
        				);
        		
        				$this->db->insert('crm_user', $data);
        }
		
		
		
        
        public function update_create_joint_crmuser($firstname,$lastname,$email,$phone_country_code,$phone,$birth_date,$city,$zipcode,$address,$tradingPlatformAccountName,$tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$currency_id,$currency_code,$accountId,$tradingPlatformAccountId,$uniquenum)
        {
        
        	$data = array(
        			//'title' => $this->input->post('title'),
        			//'slug' => $slug,
        			'firstname' => $firstname,
        			'lastname' => $lastname,
        			'country'=> $_SESSION['country'],
        			'trading_platform'=> "REAL",
        			'account_type'=> "1",
        			'currency'=> $currency_code,
        			'platform'=> "sirix",
        			'secret_data'=> $tradingPlatformAccountPassword,
        			'phone_country_code'=> $phone_country_code,
        			'phone'=> $phone,
        			'birth_date'=>$birth_date,
        			//	'platform'=>$this->input->post('platform'),
        			'email' => $email,
        			'trading_platform_accountid'=>$tradingPlatformAccountId,
        			'account_id'=>$accountId,
        			'business_unit'=>$BUSINESS_UNIT_NAME,
        			'currency_id'=>$currency_id,
        			'currency_code'=>$currency_code,
        			'name'=>$tradingPlatformAccountName,
        			 
        	);
        
        	$this->db->insert('crm_user', $data);
        }
        
        
        public function update_create_corporate_crmuser($tradingPlatformAccountName,$tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$currency_id,$currency_code,$accountId,$tradingPlatformAccountId,$date)
        {
        
        	$data = array(
        			//'title' => $this->input->post('title'),
        			//'slug' => $slug,
        			'firstname' => $this->input->post('compname'),
        			'lastname' => $this->input->post('compname'),
        			'country'=> $this->input->post('country'),
        			'trading_platform'=> "REAL",
        			'account_type'=> "1",
        			'currency'=> $currency_code,
        			'platform'=> "sirix",
        			'secret_data'=> $tradingPlatformAccountPassword,
        			'phone_country_code'=> $this->input->post('country_code'),
        			'phone'=> $this->input->post('compphone'),
        			'birth_date'=>$date,
        			//	'platform'=>$this->input->post('platform'),
        			'email' => $this->input->post('email_confirmation'),
        			'trading_platform_accountid'=>$tradingPlatformAccountId,
        			'account_id'=>$accountId,
        			'business_unit'=>$BUSINESS_UNIT_NAME,
        			'currency_id'=>$currency_id,
        			'currency_code'=>$currency_code,
        			'name'=>$tradingPlatformAccountName,
        
        	);
        
        	$this->db->insert('crm_user', $data);
        }
        
		
		
		public function create_lp_crmuser($tradingPlatformAccountName,$tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$currency_id,$currency_code,$accountId,$tradingPlatformAccountId,$date,$country,$dialingcode)
        {
        
        	$data = array(
        						//'title' => $this->input->post('title'),
        						//'slug' => $slug,
        						'firstname' => $this->input->post('first_name'),
        						'lastname' => $this->input->post('last_name'),
        						'country'=> $country,
        						'trading_platform'=> "REAL",
        						'account_type'=> $this->input->post('acctype'),
        						'currency'=> $currency_code,
        						'platform'=> $this->input->post('platformtype'),
        						'secret_data'=> $tradingPlatformAccountPassword,
        						'phone_country_code'=> $dialingcode,
        						'phone'=> $this->input->post('phone'),
        						'birth_date'=>$date,
        					//	'platform'=>$this->input->post('platform'),
        						'email' => $this->input->post('email'),
        						'trading_platform_accountid'=>$tradingPlatformAccountId,
        						'account_id'=>$accountId,
        						'business_unit'=>$BUSINESS_UNIT_NAME,
        						'currency_id'=>$currency_id,
        						'currency_code'=>$currency_code,
        						'name'=>$tradingPlatformAccountName,
        						 
        				);
        		
        				$this->db->insert('crm_user', $data);
        }
        
        public function get_account_id($username)
        {
        	$this->db->select('account_id');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('account_id');
        
        }
        public function get_country_name($country_name)
        {
        	$this->db->select('name');
        	$this->db->from('countries_country');
        	$this->db->where('iso2', $country_name);
        	return $this->db->get()->row('name');
        
        }
        
        public function get_details($username)
        {
        	$this->db->select('*');
        	$this->db->from('crm_account');
        	$this->db->where('tp_account_id', $username);
        
        	$query = $this->db->get();
        	return $result = $query->result();
        
        }
		
		
		
		public function get_crm_details($username)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        
        	$query = $this->db->get();
        	return $result = $query->result();
        
        }
		
        
        /**
         * hash_password function.
         *
         * @access private
         * @param mixed $password
         * @return string|bool could be a string on success, or bool false on failure
         */
        private function hash_password($password) {
        
        	return password_hash($password, PASSWORD_BCRYPT);
        
        }
        
}
