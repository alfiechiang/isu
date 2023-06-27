<?php

namespace App\Console\Commands;

use App\Enums\CustomerCitizenship;
use App\Enums\CustomerStatus;
use App\Models\Customer;
use App\Models\StampCustomer;
use App\Models\StoreEmployee;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ConvertLegacyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:convert-legacy-data {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $results = \DB::select('SELECT MemberId, SUM(RemainPoint) as RemainPoint, b.id, a.PointEndDate
FROM po as a join customers as b
on a.MemberId = b.legacy_system_id
GROUP BY MemberId;');

        $operator = StoreEmployee::first();

        foreach ($results as $row){
            $customer = Customer::find($row->id);
            $quantity = (int)$row->RemainPoint;
            $customer->stamps += $quantity;

           // for($x = 0; $x < (int)$row->RemainPoint; $x++) {
                $stampCustomer = new StampCustomer([
                    'store_id' => "308bb39a-08d9-11ee-8b65-0242ac1e0005",
                    'customer_id' => $row->id,
                    'expired_at' => $row->PointEndDate,
                    'type' => 'store',
                    'is_redeem' => $quantity < 0,
                    'value' => $quantity,
                    'remain_quantity' => $customer->stamps,
                ]);

                $stampCustomer->operator()->associate($operator);

                $stampCustomer->save();
            $customer->save();
           // }
        }
        dd('ok.');

        $filename = $this->argument('filename');
        $keys = ['Id', 'Name', 'Telephone', 'Gender', 'Citizenship', 'Email', 'Birthday', 'BirthdayMonth', 'Interest', 'Address', 'State', 'OpenCard', 'OpenCardDate', 'OpenCardHotel', 'SendBirthdayDate', 'CardLevel', 'CreateDate', 'CreateUserId', 'CreateUserName', 'ModifyDate', 'ModifyUserId', 'ModifyUserName', 'Long', 'Lat', 'Password', 'Introduction', 'Partner', 'StepCount', 'UpdateDate', 'AvatarPicture', 'UuId', 'LoginFirstTime', 'ContactInformation', 'Recommender', 'DeviceToken'];

        if (($open = fopen(storage_path("app/{$filename}"), "r")) !== FALSE) {
            while (($data = fgetcsv($open, 10000, ",")) !== FALSE) {
                $fillData = array_combine($keys, $data);

                $phone = $fillData['Telephone'];

                if (!preg_match('/^(?:09|9)\d{8}$/', $phone)) {
                    continue;
                }

                if (strlen($phone) === 9) {
                    $phone = '0' . $phone;
                }

                $gender = null;
                if ($fillData['Gender'] == '男') {
                    $gender = 'male';
                }

                if ($fillData['Gender'] == '女') {
                    $gender = 'female';
                }

                $birthday = null;
                if ($fillData['Birthday'] != '') {
                    $v = str_ireplace('下午', 'PM', $fillData['Birthday']);
                    $v = str_ireplace('上午', 'AM', $v);

                    $birthday = Carbon::createFromFormat('Y/n/j a h:i:s', $v);
                }

                $interest = null;
                if ($fillData['Interest'] != '') {
                    $interest = json_decode($fillData['Interest'], true);
                }

                $created_at = null;
                if ($fillData['CreateDate'] != '') {
                    $v = str_ireplace('下午', 'PM', $fillData['CreateDate']);
                    $v = str_ireplace('上午', 'AM', $v);

                    $created_at = Carbon::createFromFormat('Y/n/j a h:i:s', $v);
                }

                $updated_at = null;
                if ($fillData['ModifyDate'] != '') {
                    $v = str_ireplace('下午', 'PM', $fillData['ModifyDate']);
                    $v = str_ireplace('上午', 'AM', $v);

                    $updated_at = Carbon::createFromFormat('Y/n/j a h:i:s', $v);
                }

                $customer = Customer::wherePhone($phone)->first();

                $email = $fillData['Email'] == '' ? null : $fillData['Email'];

                $data = [
                    'name' => $fillData['Name'],
                    'phone' => $phone,
                    'password' => $fillData['Password'],
                    'citizenship' => $fillData['Citizenship'] == '外國籍' ? CustomerCitizenship::FOREIGN->value : CustomerCitizenship::NATIVE->value,
                    'status' => $fillData['State'] == 'TRUE' ? CustomerStatus::ENABLED->value : CustomerStatus::DISABLED->value,
                    'gender' => $gender,
                    'birthday' => $birthday,
                    'address' => $fillData['Address'],
//                    'interest' => $interest,
                    'legacy_system_id' => $fillData['Id'],
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];

                if($customer){
                    $customer->update($data);
                }else{
                    if($email && !Customer::whereEmail($email)->first()){
                        $data['email'] = $email;
                    }

                    Customer::create($data);
                }
            }

            fclose($open);
        }
    }
}
