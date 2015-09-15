<?php
/**
 * Created by IntelliJ IDEA.
 * User: kimbeomjun
 * Date: 15. 9. 15.
 * Time: 15:04
 */

$save_data = array(
    'merchant_uid' => '',
    'buyer_name' => '',     // 구매자 성함
    'buyer_email' => '',    // 구매자 이메일
    'buyer_tel' => '',      // 구매자 연락처
    'name' => '',           // 상품명
    'buyer_eng_name' => '',
    'buyer_addr' => '',
    'buyer_launch' => '',
);

foreach ($save_data as $key => $value) {
    if (!empty($_POST[$key])) {
        $save_data[ $key ] = $_POST[ $key];
    }
}

try {
    ParseClient::initialize($_SERVER['P_APP_ID'] ?: '', $_SERVER['P_REST_KEY'] ?: '', $_SERVER['P_MASTER_KEY'] ?: '');

// 파스에 결제 정보를 저장합니다.
    $paidInfo = ParseObject::create("PaidInfo");
    $objectId = $paidInfo->getObjectId();
    $php = $paidInfo->get("elephant");

    foreach ($api_payload as $key => $value) {
        if (!in_array($key, ['token', 'vat', 'card_number', 'expiry', 'birth', 'pwd_2digit'])) {
            if (strcmp($key, 'amount') == 0) {
                $paidInfo->set($key, (int)$value);
            } else {
                $paidInfo->set($key, $value);
            }
        }
    }

    $paidInfo->save();



    exit( json_encode( array( 'success' => true ) ) );
} catch (ParseException $ex) {
    exit( json_encode( array( 'success' => false, 'message' => "등록 정보 저장 시 에러가 발생했습니다.", 'detail' => $ex->getMessage() ) ) );
} catch (Exception $e) {
    exit( json_encode( array( 'success' => false, 'message' => "등록 정보 저장 시 에러가 발생했습니다." ) ) );
}