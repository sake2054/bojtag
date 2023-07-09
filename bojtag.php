<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://blog.sakede.su
 * @since             1.0.0
 * @package           bojtag
 *
 * @wordpress-plugin
 * Plugin Name:       BOJ Tag
 * Plugin URI:        https://blog.sakede.su
 * Description:       백준 온라인 저지(BOJ)와 solved.ac의 태그를 표시합니다.
 * Version:           r230710a
 * Author:            Sake
 * Author URI:        https://blog.sakede.su
 * License:           MIT
 * License URI:       https://github.com/sake2054/bojtag/blob/main/LICENSE
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// HTTP 상태 코드를 받아옵니다. 
function get_http_response_code( $url ) {
	$headers = get_headers( $url );
	return substr( $headers[0], 9, 3 );
}

// solved.ac 티어를 리턴합니다. 배열은 [0] = 티어를 0~31까지의 숫자로 나타낸 값, [1] = 티어 이름, [2] = 티어 색상(css용)입니다.
function solved_tier( $arg ) {

	if ( $arg == '-1' || $arg == 'nr' ) {
		$tier = array('nr', 'Not Ratable', 'nr');
	} elseif ( $arg == '0' || $arg == 'ur' ) {
		$tier = array('0', 'Unrated', 'nr');
	} elseif ( $arg == 'sp' ) {
		$tier = array('sprout', 'Sprout', 'nr');
	} elseif ( $arg == '1' || $arg == 'b5' ) {
		$tier = array('1', 'Bronze V', 'bronze');
	} elseif ( $arg == '2' || $arg == 'b4' ) {
		$tier = array('2', 'Bronze IV', 'bronze');
	} elseif ( $arg == '3' || $arg == 'b3' ) {
		$tier = array('3', 'Bronze III', 'bronze');
	} elseif ( $arg == '4' || $arg == 'b2' ) {
		$tier = array('4', 'Bronze II', 'bronze');
	} elseif ( $arg == '5' || $arg == 'b1' ) {
		$tier = array('5', 'Bronze I', 'bronze');
	} elseif ( $arg == '6' || $arg == 's5' ) {
		$tier = array('6', 'Silver V', 'silver');
	} elseif ( $arg == '7' || $arg == 's4' ) {
		$tier = array('7', 'Silver IV', 'silver');
	} elseif ( $arg == '8' || $arg == 's3' ) {
		$tier = array('8', 'Silver III', 'silver');
	} elseif ( $arg == '9' || $arg == 's2' ) {
		$tier = array('9', 'Silver II', 'silver');
	} elseif ( $arg == '10' || $arg == 's1' ) {
		$tier = array('10', 'Silver I', 'silver');
	} elseif ( $arg == '11' || $arg == 'g5' ) {
		$tier = array('11', 'Gold V', 'gold');
	} elseif ( $arg == '12' || $arg == 'g4' ) {
		$tier = array('12', 'Gold IV', 'gold');
	} elseif ( $arg == '13' || $arg == 'g3' ) {
		$tier = array('13', 'Gold III', 'gold');
	} elseif ( $arg == '14' || $arg == 'g2' ) {
		$tier = array('14', 'Gold II', 'gold');
	} elseif ( $arg == '15' || $arg == 'g1' ) {
		$tier = array('15', 'Gold I', 'gold');
	} elseif ( $arg == '16' || $arg == 'p5' ) {
		$tier = array('16', 'Platinum V', 'platinum');
	} elseif ( $arg == '17' || $arg == 'p4' ) {
		$tier = array('17', 'Platinum IV', 'platinum');
	} elseif ( $arg == '18' || $arg == 'p3' ) {
		$tier = array('18', 'Platinum III', 'platinum');
	} elseif ( $arg == '19' || $arg == 'p2' ) {
		$tier = array('19', 'Platinum II', 'platinum');
	} elseif ( $arg == '20' || $arg == 'p1' ) {
		$tier = array('20', 'Platinum I', 'platinum');
	} elseif ( $arg == '21' || $arg == 'd5' ) {
		$tier = array('21', 'Diamond V', 'diamond');
	} elseif ( $arg == '22' || $arg == 'd4' ) {
		$tier = array('22', 'Diamond IV', 'diamond');
	} elseif ( $arg == '23' || $arg == 'd3' ) {
		$tier = array('23', 'Diamond III', 'diamond');
	} elseif ( $arg == '24' || $arg == 'd2' ) {
		$tier = array('24', 'Diamond II', 'diamond');
	} elseif ( $arg == '25' || $arg == 'd1' ) {
		$tier = array('25', 'Diamond I', 'diamond');
	} elseif ( $arg == '26' || $arg == 'r5' ) {
		$tier = array('26', 'Ruby V', 'ruby');
	} elseif ( $arg == '27' || $arg == 'r4' ) {
		$tier = array('27', 'Ruby IV', 'ruby');
	} elseif ( $arg == '28' || $arg == 'r3' ) {
		$tier = array('28', 'Ruby III', 'ruby');
	} elseif ( $arg == '29' || $arg == 'r2' ) {
		$tier = array('29', 'Ruby II', 'ruby');
	} elseif ( $arg == '30' || $arg == 'r1' ) {
		$tier = array('30', 'Ruby I', 'ruby');
	} elseif ( $arg == '31' || $arg == 'm' ) {
		$tier = array('31', 'Master', 'master');
	}

	return $tier;
}

// BOJ 문제 태그
function boj_label( $arg ) {

	if ( $arg == 'spj' ) {
		$result = array('spj', '스페셜 저지', 'Special Judge');
	} elseif ( $arg == 'partial' ) {
		$result = array('partial', '점수', 'Points');
	} elseif ( $arg == 'full' ) {
		$result = array('full', '전체 채점', 'Full');
	} elseif ( $arg == 'random-killer' ) {
		$result = array('random-killer', '랜덤 방지', 'Random');
	} elseif ( $arg == 'unofficial' ) {
		$result = array('unofficial', '번외', 'Extra');
	} elseif ( $arg == 'preparing' ) {
		$result = array('preparing', '채점 준비 중', 'Preparing');
	} elseif ( $arg == 'deleted' ) {
		$result = array('deleted', '삭제', 'Deleted');
	} elseif ( $arg == 'subtask' ) {
		$result = array('subtask', '서브태스크', 'Subtask');
	} elseif ( $arg == 'ac' ) {
		$result = array('ac', '성공', 'Success');
	} elseif ( $arg == 'pac' ) {
		$result = array('pac', '부분 성공', 'Partial Success');
	} elseif ( $arg == 'wa' ) {
		$result = array('wa', '실패', 'Failure');
	} elseif ( $arg == 'language-restrict' ) {
		$result = array('language-restrict', '언어 제한', 'Language');
	} elseif ( $arg == 'submit-limit' ) {
		$result = array('submit-limit', '제출 횟수 제한', 'Submit');
	} elseif ( $arg == 'interactive' ) {
		$result = array('interactive', '인터랙티브', 'Interactive');
	} elseif ( $arg == 'func' ) {
		$result = array('func', '함수 구현', 'Function');
	} elseif ( $arg == 'two-steps' ) {
		$result = array('two-steps', '투 스텝', 'Two Steps');
	} elseif ( $arg == 'class' ) {
		$result = array('class', '클래스 구현', 'Class');
	} elseif ( $arg == 'feedback' ) {
		$result = array('feedback', '피드백', 'Feedback');
	} elseif ( $arg == 'time-acc' ) {
		$result = array('time-acc', '시간 누적', 'Time Accumulation');
	} elseif ( $arg == 'multilang' ) {
		$result = array('multilang', '다국어', 'Multilingual');
	} elseif ( $arg == 'bookmark' ) {
		$result = array('bookmark', '북마크', 'Bookmark');
	}

    return $result;
}

// BOJ 채점 결과
function boj_result( $arg ) {

	if ( $arg == '0' || $arg == 'wait' ) {
		$result = array('wait', '기다리는 중', 'Pending');
	} elseif ( $arg == '1' || $arg == 'rejudge-wait' ) {
		$result = array('rejudge-wait', '재채점을 기다리는 중', 'Pending Rejudge');
	} elseif ( $arg == '2' || $arg == 'compile' ) {
		$result = array('compile', '채점 준비 중', 'Preparing for Judging');
	} elseif ( $arg == '3' || $arg == 'judging' ) {
		$result = array('judging', '채점 중', 'Judging');
	} elseif ( $arg == '4' || $arg == 'ac' ) {
		$result = array('ac', '맞았습니다!!', 'Accepted');
	} elseif ( $arg == '5' || $arg == 'pe' ) {
		$result = array('pe', '출력 형식이 잘못되었습니다', 'Presentation Error');
	} elseif ( $arg == '6' || $arg == 'wa' ) {
		$result = array('wa', '틀렸습니다', 'Wrong Answer');
	} elseif ( $arg == '7' || $arg == 'tle' ) {
		$result = array('tle', '시간 초과', 'Time Limit Exceeded');
	} elseif ( $arg == '8' || $arg == 'mle' ) {
		$result = array('mle', '메모리 초과', 'Memory Limit Exceeded');
	} elseif ( $arg == '9' || $arg == 'ole' ) {
		$result = array('ole', '출력 초과', 'Output Limit Exceeded');
	} elseif ( $arg == '10' || $arg == 'rte' ) {
		$result = array('rte', '런타임 에러', 'Runtime Error');
	} elseif ( $arg == '11' || $arg == 'ce' ) {
		$result = array('ce', '컴파일 에러', 'Compilation Error');
	} elseif ( $arg == '12' || $arg == 'co' ) {
		$result = array('co', '채점 불가', 'Unavailable');
	} elseif ( $arg == '13' || $arg == 'del' ) {
		$result = array('del', '삭제된 제출', 'Deleted');
	} elseif ( $arg == '14' || $arg == 'remain' ) {
		$result = array('del', '초 후 채점 시작', '');
	} elseif ( $arg == '15' || $arg == 'pac' ) {
		$result = array('pac', '맞았습니다!!', 'Partially Accepted');
	} elseif ( $arg == '16' || $arg == 'rtereason' ) {
		$result = array('judging', '런타임 에러 이유를 찾는 중', 'Finding RTE reason');
	} elseif ( $arg == 'nojudge' ) {
		$result = array('nojudge', '채점하지 않음', '');
	}

	return $result;
}

// solved.ac 아레나 티어
function arena_tier( $arg ) {

	$arg = strtolower($arg);

    if ( $arg == '0' || $arg == 'ur' ) {
        $tier = array('0', 'Unrated', 'ur');
    } elseif ( $arg == '1' || $arg == 'c' ) {
        $tier = array('1', 'C', 'c');
    } elseif ( $arg == '2' || $arg == 'c+' ) {
        $tier = array('2', 'C+', 'c');
    } elseif ( $arg == '3' || $arg == 'b' ) {
        $tier = array('3', 'B', 'b');
    } elseif ( $arg == '4' || $arg == 'b+' ) {
        $tier = array('4', 'B+', 'b');
    } elseif ( $arg == '5' || $arg == 'a' ) {
        $tier = array('5', 'A', 'a');
    } elseif ( $arg == '6' || $arg == 'a+' ) {
        $tier = array('6', 'A+', 'a');
    } elseif ( $arg == '7' || $arg == 's' ) {
        $tier = array('7', 'S', 's');
    } elseif ( $arg == '8' || $arg == 's+' ) {
        $tier = array('8', 'S+', 's');
    } elseif ( $arg == '9' || $arg == 'ss' ) {
        $tier = array('9', 'SS', 'ss');
    } elseif ( $arg == '10' || $arg == 'ss+' ) {
        $tier = array('10', 'SS+', 'ss');
    } elseif ( $arg == '11' || $arg == 'sss' ) {
        $tier = array('11', 'SSS', 'sss');
    } elseif ( $arg == '12' || $arg == 'sss+' ) {
        $tier = array('12', 'SSS+', 'sss');
    } elseif ( $arg == '13' || $arg == 'x' ) {
        $tier = array('13', 'X', 'x');
    }

    return $tier;
}

// solved.ac 아레나 레이팅 기반 티어 계산
function arena_rating( $arg ) {

    $rating = intval( $arg );
    if ( $rating == 0 ) {
        $tier = '0';
    } elseif ( $rating >= 1 && $rating <= 399 ) {
        $tier = '1';
    } elseif ( $rating >= 400 && $rating <= 799 ) {
        $tier = '2';
    } elseif ( $rating >= 800 && $rating <= 999 ) {
        $tier = '3';
    } elseif ( $rating >= 1000 && $rating <= 1199 ) {
        $tier = '4';
    } elseif ( $rating >= 1200 && $rating <= 1399 ) {
        $tier = '5';
    } elseif ( $rating >= 1400 && $rating <= 1599 ) {
        $tier = '6';
    } elseif ( $rating >= 1600 && $rating <= 1799 ) {
        $tier = '7';
    } elseif ( $rating >= 1800 && $rating <= 1999 ) {
        $tier = '8';
    } elseif ( $rating >= 2000 && $rating <= 2199 ) {
        $tier = '9';
    } elseif ( $rating >= 2200 && $rating <= 2399 ) {
        $tier = '10';
    } elseif ( $rating >= 2400 && $rating <= 2599 ) {
        $tier = '11';
    } elseif ( $rating >= 2600 && $rating <= 2999 ) {
        $tier = '12';
    } elseif ( $rating >= 3000 ) {
        $tier = '13';
    }

    return arena_tier( $tier );

}

// 쇼트코드
function bojtag( $atts ) {

	// Attribute 값 가져오기, l, r, t, u, p, at, ar 중 하나, s, re, en은 있어도 되고 없어도 됨
	$atts = shortcode_atts(
		array(
			'l' => '',  // BOJ Label
			'r' => '',  // BOJ Result
			't' => '',  // solved.ac Tier
			'u' => '',  // solved.ac User Information
			'p' => '',  // BOJ Problem Information
			'at' => '', // solved.ac Arena Tier
			'ar' => '', // solved.ac Arena Rating
			's' => '',  // Custom String
			're' => '', // Rejudge
			'en' => '', // English
		),
		$atts,
		'boj'
	);

	if ( $atts['l'] !== '' ) {

		$l = boj_label( strtolower($atts['l']) );

		if ( $atts['s'] == '' ) {
			if ( $atts['en'] == '' ) {
				return '<span class="boj-l boj-l-'.$l[0].'">'.$l[1].'</span>';
			} else {
				return '<span class="boj-l boj-l-'.$l[0].'">'.$l[2].'</span>';
			}
		} else {
			return '<span class="boj-l boj-l-'.$l[0].'">'.$atts['s'].'</span>';
		}

	} elseif ( $atts['r'] !== '' ) {

		$r = boj_result( strtolower($atts['r']) );

		if ( $atts['re'] == '1' ) {
			$re = 'boj-r-rejudge ';
		} else {
			$re = '';
		}

		if ( $atts['s'] == '' ) {
			if ( $atts['en'] == '' ) {
				return '<span class="'.$re.'boj-r-'.$r[0].'">'.$r[1].'</span>';
			} else {
				return '<span class="'.$re.'boj-r-'.$r[0].'">'.$r[2].'</span>';
			}
		} else {
			if ( $atts['r'] == '14' || $atts['r'] == 'remain' ) {
				return '<span class="'.$re.'boj-r-'.$r[0].'">'.$atts['s'].$r['1'].'</span>';
			} else {
				return '<span class="'.$re.'boj-r-'.$r[0].'">'.$atts['s'].'</span>';
			}
		}

	} elseif ( $atts['t'] !== '' ) {

		$t = solved_tier( strtolower($atts['t']) );

		if ( $atts['s'] == '1' ) {
			return '<img src="https://static.solved.ac/tier_small/'.$t[0].'.svg" class="boj-t-img boj-t-'.$t[0].'" alt="'.$t[1].'"><span class="boj-t-text-'.$t[2].'"> '.$t[1].'</span>';
		} else {
			return '<img src="https://static.solved.ac/tier_small/'.$t[0].'.svg" class="boj-t-img boj-t-'.$t[0].'" alt="'.$t[1].'">';
		}

	} elseif ( $atts['u'] !== '' ) {

		$handle = strtolower($atts['u']);
		$file = __DIR__.'/users/'.$handle.'.json';
		$solvedurl = 'https://solved.ac/api/v3/user/show?handle='.$handle;
		
		if ( $handle == 'solvedac' ) { // solvedac 계정
			if ($atts['s'] == 1) {
				return '<a href="https://solved.ac/profile/solvedac" target="_blank" class="boj-u"><img src="https://static.solved.ac/tier_small/admin.svg" class="boj-t-img"><span>&nbsp;</span><img src="https://static.solved.ac/uploads/profile/64x64/c6ee5f2d3a85d783ca494e77423bb5d295bbc534.png" class="boj-u-profile"><span class="boj-t-text-admin">&nbsp;solvedac</span></a>';
			} else {
				return '<a href="https://solved.ac/profile/solvedac" target="_blank" class="boj-u"><img src="https://static.solved.ac/tier_small/admin.svg" class="boj-t-img"><span class="boj-t-text-admin">&nbsp;solvedac</span></a>';
			}
		} else if ( file_exists($file) ) {
			$filetime = filemtime($file);
			$now = time();
			if (($now - $filetime) >= 86400) {
				$userinfo = file_get_contents($solvedurl);
				file_put_contents($file, $userinfo);
			}
		} else {
			if ( get_http_response_code($solvedurl) != '200' ) {
				return 'ERROR :(';
			} else {
				$userinfo = file_get_contents($solvedurl);
				file_put_contents($file, $userinfo);
			}
		}
		
		$info = json_decode(file_get_contents($file), true);
		$t = solved_tier($info['tier']);

		if ( $atts['s'] == '1' ) {
			$profileImage = $info['profileImageUrl'];
			if ($profileImage == null) {
				$profileImage = 'https://static.solved.ac/misc/64x64/default_profile.png';
			} else {
				$profileImage = 'https://static.solved.ac/uploads/profile/64x64/'.substr($profileImage, 41);
			}

			return '<a href="https://solved.ac/profile/'.$handle.'" target="_blank" class="boj-u"><img src="https://static.solved.ac/tier_small/'.$t[0].'.svg" class="boj-t-img"><span>&nbsp;</span><img src="'.$profileImage.'" class="boj-u-profile"><span class="boj-t-text-'.$t[2].'">&nbsp;'.$handle.'</span></a>';
		} else {
			return '<a href="https://solved.ac/profile/'.$handle.'" target="_blank" class="boj-u"><img src="https://static.solved.ac/tier_small/'.$t[0].'.svg" class="boj-t-img"><span class="boj-t-text-'.$t[2].'">&nbsp;'.$handle.'</span></a>';
		}

	} elseif ( $atts['p'] !== '' ) {

		$problemId = $atts['p'];
		$file = __DIR__.'/problems/'.$problemId.'.json';
		$solvedurl = 'https://solved.ac/api/v3/problem/show?problemId='.$problemId;
		$bojurl = 'https://www.acmicpc.net/problem/'.$problemId;
	
		if (file_exists($file)) {
			$filetime = filemtime($file);
			$now = time();
			if (($now - $filetime) >= 259200) {
				$problemInfo = file_get_contents($solvedurl);
				file_put_contents($file, $problemInfo);
			}
		} else {
			if (get_http_response_code($solvedurl) != '200') {
				return 'ERROR :(';
			} else {
				$problemInfo = file_get_contents($solvedurl);
				file_put_contents($file, $problemInfo);
			}
		}
	
		$info = json_decode(file_get_contents($file), true);
		$t = solved_tier($info['level']);
	
		return '<a href="'.$bojurl.'" target="_blank" class="boj-p"><img src="https://static.solved.ac/tier_small/'.$t[0].'.svg" class="boj-t-img"><span class="boj-p-text">&nbsp;'.$info['problemId'].'. '.$info['titleKo'].'</span></a>';

	} elseif ( $atts['at'] !== '' ) {

		$t = arena_tier( strtolower($atts['at']) );
	
		return '<img src="https://static.solved.ac/tier_arena/'.$t[0].'.svg" class="boj-a-img boj-a-'.$t[0].'" alt="'.$t[1].'">';
	
	} elseif ( $atts['ar'] !== '' ) {
	
		$t = arena_rating( $atts['ar'] );
	
		return '<img src="https://static.solved.ac/tier_arena/'.$t[0].'.svg" class="boj-a-img boj-a-'.$t[0].'" alt="'.$t[1].'"><span class="boj-a-text boj-a-text-'.$t[2].'"> '.$atts['ar'].'</span>';
	
	}

}

add_shortcode( 'boj', 'bojtag' );

function shortcode_test($atts) {

    extract(shortcode_atts(array(
            'blob' => isset($atts[0]) ? $atts[0] : '' ,
            ), $atts));

    return ':blob' . $blob . ':';
}

add_shortcode ('blob','shortcode_test');

// CSS 적용하기
wp_register_style( 'boj-style', plugins_url('style.css', __FILE__), array(), filemtime(__DIR__.'/style.css') );
wp_enqueue_style( 'boj-style' );

?>
