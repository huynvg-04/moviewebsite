<?php

namespace App\Http\Controllers;

use App\Wallet;
use App\User;
use App\Nation;
use App\Cate;
use App\Year;
use App\WalletCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Schema;

class WalletController extends Controller
{
    public function getWallet()
    {
        $cate = Cate::all();
        $nation = Nation::all();
        $year = Year::all();
        $username = session('username_12c4movies');
        $user = User::where('username', $username)->first();
        $user_id = $user->id;
        $wallet = Wallet::all();
        $walletCharge = WalletCharge::where('user_id', $user_id)->paginate(10);
        return view('user.wallet', compact('cate', 'nation', 'year', 'user', 'wallet', 'walletCharge'));
    }
    public function getChargeWallet()
    {
        $cate = Cate::all();
        $nation = Nation::all();
        $year = Year::all();
        return view('user.chargeWallet', compact('cate', 'nation', 'year'));
    }
    // public function postChargeWallet(Request $request)
    // {
    //     $request->validate([
    //         'amount' => 'required|numeric|min:10000',  // Đảm bảo số tiền ít nhất là 10.000 VND
    //     ], [
    //         'amount.min' => 'Số tiền nạp phải lớn hơn hoặc bằng 10.000 VND',  // Thông báo lỗi tùy chỉnh
    //     ]);

    //     $username = session('username_12c4movies');
    //     $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    //     $vnp_Returnurl = "http://localhost/webxemphim/wallet/saveChargeWallet/" . $username;
    //     $vnp_TmnCode = "0AG3YTI9"; //Mã website tại VNPAY
    //     $vnp_HashSecret = "6F6BZJ0XHW106LYGULFC5BYC0ZQWOIOK"; //Chuỗi bí mật

    //     $vnp_TxnRef = date('YmdHis');
    //     $vnp_OrderInfo = "Nạp tiền vào ví";
    //     $vnp_OrderType = "250006";
    //     $vnp_Amount = $request->amount * 100;
    //     $vnp_Locale = "vn";
    //     $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
    //     $inputData = array(
    //         "vnp_Version" => "2.0.0",
    //         "vnp_TmnCode" => $vnp_TmnCode,
    //         "vnp_Amount" => $vnp_Amount,
    //         "vnp_Command" => "pay",
    //         "vnp_CreateDate" => date('YmdHis'),
    //         "vnp_CurrCode" => "VND",
    //         "vnp_IpAddr" => $vnp_IpAddr,
    //         "vnp_Locale" => $vnp_Locale,
    //         "vnp_OrderInfo" => $vnp_OrderInfo,
    //         "vnp_OrderType" => $vnp_OrderType,
    //         "vnp_ReturnUrl" => $vnp_Returnurl,
    //         "vnp_TxnRef" => $vnp_TxnRef,
    //     );
    //     ksort($inputData);
    //     $query = "";
    //     $i = 0;
    //     $hashdata = "";
    //     foreach ($inputData as $key => $value) {
    //         if ($i == 1) {
    //             $hashdata .= '&' . $key . "=" . $value;
    //         } else {
    //             $hashdata .= $key . "=" . $value;
    //             $i = 1;
    //         }
    //         $query .= urlencode($key) . "=" . urlencode($value) . '&';
    //     }

    //     $vnp_Url = $vnp_Url . "?" . $query;
    //     if (isset($vnp_HashSecret)) {
    //         $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret); // Mã hóa dữ liệu
    //         $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    //     }

    //     return redirect($vnp_Url);
    // }

    // public function saveChargeWallet($username)
    // {

    //     $username = session('username_12c4movies');
    //     $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    //     $vnp_TmnCode = "0AG3YTI9"; 
    //     $vnp_HashSecret = "6F6BZJ0XHW106LYGULFC5BYC0ZQWOIOK"; 
    //     $inputData = array();
    //     $returnData = array();

    //     foreach ($_GET as $key => $value) {
    //         if (substr($key, 0, 4) == "vnp_") {
    //             $inputData[$key] = $value;
    //         }
    //     }

    //     $vnp_SecureHash = $inputData['vnp_SecureHash'];
    //     unset($inputData['vnp_SecureHash']);
    //     ksort($inputData);
    //     $hashData = "";

    //     foreach ($inputData as $key => $value) {
    //         $hashData .= urlencode($key) . '=' . urlencode($value) . '&';
    //     }
    //     $hashData = rtrim($hashData, '&');

    //     $vnpTranId = $inputData['vnp_TransactionNo']; //Mã giao dịch tại VNPAY
    //     $vnp_BankCode = $inputData['vnp_BankCode']; //Ngân hàng thanh toán
    //     $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
    //     $Status = 0;
    //     $orderId = $inputData['vnp_TxnRef'];
    //     $amount = $inputData['vnp_Amount'];

    //     try {
    //         //Check Orderid
    //         //Kiểm tra checksum của dữ liệu
    //         if ($secureHash == $vnp_SecureHash  && $_GET['vnp_ResponseCode'] == '00') {
    //             //Cài đặt Code cập nhật kết quả thanh toán, tình trạng đơn hàng vào DB
    //             //
    //             $user = User::where('username', $username)->first();
    //             $user_id = $user->id;
    //             $wallet = Wallet::where('user_id', $user_id)->first();
    //             $wallet_id = $wallet->id;
    //             $wallet_charge = new WalletCharge();
    //             $wallet_charge->user_id = $user_id;
    //             $wallet_charge->wallet_id = $wallet_id;
    //             $wallet_charge->orderId = $orderId;
    //             $wallet_charge->money = $amount / 100;
    //             $wallet_charge->save();
    //             $wallet->money = $wallet->money + ($amount / 100);
    //             $wallet->save();

    //             $returnData['RspCode'] = '00';
    //             $returnData['Message'] = 'Confirm Success';
    //             $thongbao_level = 'success';
    //             $thongbao = "<b>Nạp tiền vào ví thành công!</b>";
    //         } else {
    //             $returnData['RspCode'] = '97';
    //             $returnData['Message'] = 'Chu ky khong hop le';
    //             $thongbao_level = 'danger';
    //             $thongbao = "<b>Nạp tiền vào ví thất bại!</b>";
    //         }
    //     } catch (Exception $e) {
    //         $returnData['RspCode'] = '99';
    //         $returnData['Message'] = 'Unknow error';
    //         $thongbao_level = 'danger';
    //         $thongbao = "<b>Nạp tiền vào ví thất bại!</b>";
    //     }
    //     return redirect()->route('user.getWallet')->with(['thongbao_level' => $thongbao_level, 'thongbao' => $thongbao]);
    // }



    /**
     * Tạo payment URL để nạp tiền vào ví
     */
    public function postChargeWallet(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
        ], [
            'amount.min' => 'Số tiền nạp phải lớn hơn hoặc bằng 10.000 VND',
        ]);

        $username = session('username_12c4movies');
        if (!$username) {
            return redirect()->back()->with([
                'thongbao_level' => 'danger',
                'thongbao' => '<b>Vui lòng đăng nhập để sử dụng tính năng này!</b>'
            ]);
        }

        //   Cấu hình VNPay
        $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost/webxemphim/wallet/saveChargeWallet/" . $username;
        $vnp_TmnCode = "1SU5O285";
        $vnp_HashSecret = "9ASGEUO2Y22KFU46MDZNUFQB58QUBIMH";

        //   Thông tin giao dịch
        $vnp_TxnRef = date('YmdHis') . rand(1000, 9999); // Tránh trùng lặp
        $vnp_OrderInfo = "Nap tien vao vi - " . $username;
        $vnp_OrderType = "250006";
        $vnp_Amount = (int)($request->amount * 100);
        $vnp_Locale = "vn";
        $vnp_IpAddr = $request->ip();

        $inputData = [
            "vnp_Version" => "2.1.0", //   Cập nhật version mới
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        // 🔥 Loại bỏ tham số rỗng
        $inputData = array_filter($inputData, function ($value) {
            return $value !== '' && $value !== null;
        });

        ksort($inputData);

        // 🔥 CÁCH TẠO HASH MỚI theo VNPay 2024-2025
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        //   Tạo chữ ký HMAC-SHA512
        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        //   Tạo URL thanh toán
        $query = http_build_query($inputData, '', '&');
        $paymentUrl = $vnp_Url . "?" . $query . '&vnp_SecureHash=' . $vnpSecureHash;

        // 🛠️ Log để debug
        Log::info('VNPay Wallet Charge Created', [
            'username' => $username,
            'amount' => $vnp_Amount,
            'txnRef' => $vnp_TxnRef,
            'hashData' => $hashData
        ]);

        return redirect($paymentUrl);
    }

    /**
     * Xử lý callback từ VNPay sau khi thanh toán
     */
    public function saveChargeWallet(Request $request, $username = null)
    {
        $vnp_HashSecret = "9ASGEUO2Y22KFU46MDZNUFQB58QUBIMH";

        //   Lấy username từ session hoặc parameter
        $username = $username ?? session('username_12c4movies');
        if (!$username) {
            return redirect()->route('user.getWallet')->with([
                'thongbao_level' => 'danger',
                'thongbao' => '<b>Không xác định được người dùng!</b>'
            ]);
        }

        //   Lấy tất cả tham số VNPay
        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        //   Validation cơ bản
        if (empty($inputData['vnp_SecureHash'])) {
            return redirect()->route('user.getWallet')->with([
                'thongbao_level' => 'danger',
                'thongbao' => '<b>Thiếu chữ ký xác thực!</b>'
            ]);
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);

        // 🔥 QUAN TRỌNG: Loại bỏ các tham số rỗng
        $inputData = array_filter($inputData, function ($value) {
            return $value !== '' && $value !== null;
        });

        ksort($inputData);

        // 🔥 CÁCH TẠO HASH MỚI giống như lúc tạo payment
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        //   Tạo chữ ký để so sánh
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        //   Lấy thông tin giao dịch
        $vnpTranId = $inputData['vnp_TransactionNo'] ?? '';
        $vnp_BankCode = $inputData['vnp_BankCode'] ?? '';
        $orderId = $inputData['vnp_TxnRef'] ?? '';
        $amount = (int)($inputData['vnp_Amount'] ?? 0);
        $responseCode = $request->vnp_ResponseCode;

        // 🛠️ Debug log
        Log::info('VNPay Wallet Callback Debug', [
            'username' => $username,
            'hashData' => $hashData,
            'calculated_hash' => $secureHash,
            'received_hash' => $vnp_SecureHash,
            'response_code' => $responseCode,
            'hash_match' => ($secureHash === $vnp_SecureHash)
        ]);

        try {
            //   Kiểm tra chữ ký và mã phản hồi
            if ($secureHash === $vnp_SecureHash && $responseCode == '00') {

                //   Tìm user và wallet
                $user = User::where('username', $username)->first();
                if (!$user) {
                    throw new Exception('Không tìm thấy người dùng: ' . $username);
                }

                $wallet = Wallet::where('user_id', $user->id)->first();
                if (!$wallet) {
                    // Tạo ví mới nếu chưa có
                    $wallet = new Wallet();
                    $wallet->user_id = $user->id;
                    $wallet->money = 0;
                    $wallet->save();
                }

                //   Kiểm tra giao dịch đã tồn tại chưa (tránh duplicate)
                $existingCharge = WalletCharge::where('orderId', $orderId)->first();
                if ($existingCharge) {
                    Log::warning('Duplicate wallet charge attempt', [
                        'orderId' => $orderId,
                        'username' => $username
                    ]);
                    return redirect()->route('user.getWallet')->with([
                        'thongbao_level' => 'warning',
                        'thongbao' => '<b>Giao dịch này đã được xử lý trước đó!</b>'
                    ]);
                }

                //   Lưu thông tin nạp tiền
                $chargeAmount = $amount / 100; // VNPay trả về số tiền x100

                $wallet_charge = new WalletCharge();
                $wallet_charge->user_id = $user->id;
                $wallet_charge->wallet_id = $wallet->id;
                $wallet_charge->orderId = $orderId;
                $wallet_charge->money = $chargeAmount;

                //   Kiểm tra column tồn tại trước khi gán giá trị
                if (Schema::hasColumn('wallet_charges', 'vnp_transaction_no')) {
                    $wallet_charge->vnp_transaction_no = $vnpTranId;
                }
                if (Schema::hasColumn('wallet_charges', 'vnp_bank_code')) {
                    $wallet_charge->vnp_bank_code = $vnp_BankCode;
                }
                if (Schema::hasColumn('wallet_charges', 'status')) {
                    $wallet_charge->status = 'completed';
                }

                $wallet_charge->save();

                //   Cập nhật số dư ví
                $wallet->money = $wallet->money + $chargeAmount;
                $wallet->save();

                //   Log thành công
                Log::info('Wallet charged successfully', [
                    'user_id' => $user->id,
                    'username' => $username,
                    'amount' => $chargeAmount,
                    'orderId' => $orderId,
                    'vnp_transaction_no' => $vnpTranId,
                    'new_balance' => $wallet->money
                ]);

                return redirect()->route('user.getWallet')->with([
                    'thongbao_level' => 'success',
                    'thongbao' => '<b>Nạp tiền vào ví thành công!</b><br>Số tiền: ' . number_format($chargeAmount, 0, ',', '.') . ' VND'
                ]);
            } else {
                //   Giao dịch thất bại
                Log::warning('VNPay wallet charge failed', [
                    'username' => $username,
                    'response_code' => $responseCode,
                    'hash_match' => ($secureHash === $vnp_SecureHash),
                    'orderId' => $orderId,
                    'calculated_hash' => $secureHash,
                    'received_hash' => $vnp_SecureHash
                ]);

                $errorMessage = $this->getVnpayErrorMessage($responseCode);

                return redirect()->route('user.getWallet')->with([
                    'thongbao_level' => 'danger',
                    'thongbao' => '<b>Nạp tiền thất bại!</b><br>' . $errorMessage
                ]);
            }
        } catch (Exception $e) {
            //   Xử lý lỗi
            Log::error('Wallet charge error', [
                'error' => $e->getMessage(),
                'username' => $username,
                'orderId' => $orderId,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('user.getWallet')->with([
                'thongbao_level' => 'danger',
                'thongbao' => '<b>Có lỗi xảy ra!</b><br>' . $e->getMessage()
            ]);
        }
    }

    /**
     * Lấy thông báo lỗi từ mã phản hồi VNPay
     */
    private function getVnpayErrorMessage($responseCode)
    {
        $errorMessages = [
            '07' => 'Giao dịch bị nghi ngờ (liên quan tới lừa đảo)',
            '09' => 'Thẻ/Tài khoản chưa đăng ký dịch vụ InternetBanking',
            '10' => 'Xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Đã hết hạn chờ thanh toán',
            '12' => 'Thẻ/Tài khoản bị khóa',
            '13' => 'Nhập sai mật khẩu xác thực giao dịch (OTP)',
            '24' => 'Khách hàng hủy giao dịch',
            '51' => 'Tài khoản không đủ số dư',
            '65' => 'Tài khoản vượt quá hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng thanh toán đang bảo trì',
            '79' => 'Nhập sai mật khẩu thanh toán quá số lần quy định',
            '97' => 'Chữ ký không hợp lệ',
            '99' => 'Lỗi không xác định'
        ];

        return $errorMessages[$responseCode] ?? 'Lỗi không xác định (Mã: ' . $responseCode . ')';
    }




    /**
     * Lấy thông báo lỗi từ mã phản hồi VNPay
     */

    public function walletCharge()
    {
        $user = User::all();
        $walletCharge = WalletCharge::all();
        return view('admin.wallet_charge.list', compact('walletCharge', 'user'));
    }
}
