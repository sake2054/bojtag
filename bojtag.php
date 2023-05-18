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
 * Version:           r230518a
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
function get_http_response_code($url) {
	$headers = get_headers($url);
	return substr($headers[0], 9, 3);
}

// 솔브드 티어를 리턴합니다. 배열은 [0] = 티어를 0~31까지의 숫자로 나타낸 값, [1] = 티어 이름, [2] = 티어 색상(css용)입니다.
// TODO 새싹티어 이쁘게 바꾸기

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

// 쇼트코드
function bojtag( $atts ) {

	// Attribute 값 가져오기, l, r, t, u 중 하나, s, re는 있어도 되고 없어도 됨
	$atts = shortcode_atts(
		array(
			'l' => '',
			'r' => '',
			't' => '',
			'u' => '',
			's' => '',
			're' => '',
		),
		$atts,
		'boj'
	);

	if ( $atts['l'] !== '' ) {
		if ( $atts['l'] == 'spj' ) {
			return '<span class="boj-l boj-l-spj">스페셜 저지</span>';
		} elseif ( $atts['l'] == 'partial' ) {
			return '<span class="boj-l boj-l-partial">점수</span>';
		} elseif ( $atts['l'] == 'full' ) {
			return '<span class="boj-l boj-l-full">전체 채점</span>';
		} elseif ( $atts['l'] == 'random-killer' ) {
			return '<span class="boj-l boj-l-random-killer">랜덤 방지</span>';
		} elseif ( $atts['l'] == 'unofficial' ) {
			return '<span class="boj-l boj-l-unofficial">번외</span>';
		} elseif ( $atts['l'] == 'preparing' ) {
			return '<span class="boj-l boj-l-preparing">채점 준비 중</span>';
		} elseif ( $atts['l'] == 'deleted' ) {
			return '<span class="boj-l boj-l-deleted">삭제</span>';
		} elseif ( $atts['l'] == 'subtask' ) {
			return '<span class="boj-l boj-l-subtask">서브태스크</span>';
		} elseif ( $atts['l'] == 'ac' ) {
			return '<span class="boj-l boj-l-ac">성공</span>';
		} elseif ( $atts['l'] == 'pac' ) {
			return '<span class="boj-l boj-l-pac">부분 성공</span>';
		} elseif ( $atts['l'] == 'wa' ) {
			return '<span class="boj-l boj-l-wa">실패</span>';
		} elseif ( $atts['l'] == 'language-restrict' ) {
			return '<span class="boj-l boj-l-language-restrict">언어 제한</span>';
		} elseif ( $atts['l'] == 'submit-limit' ) {
			if ($atts['s'] == '' ) {
				return '<span class="boj-l boj-l-submit-limit">제출 횟수 제한</span>';
			} else {
				return '<span class="boj-l boj-l-submit-limit">제출 횟수 제한 '.$atts['s'].'</span>';
			}
		} elseif ( $atts['l'] == 'interactive' ) {
			return '<span class="boj-l boj-l-interactive">인터랙티브</span>';
		} elseif ( $atts['l'] == 'func' ) {
			return '<span class="boj-l boj-l-func">함수 구현</span>';
		} elseif ( $atts['l'] == 'two-steps' ) {
			return '<span class="boj-l boj-l-two-steps">투 스텝</span>';
		} elseif ( $atts['l'] == 'class' ) {
			return '<span class="boj-l boj-l-class">클래스 구현</span>';
		} elseif ( $atts['l'] == 'feedback' ) {
			return '<span class="boj-l boj-l-feedback">피드백</span>';
		} elseif ( $atts['l'] == 'time-acc' ) {
			return '<span class="boj-l boj-l-time-acc">시간 누적</span>';
		} 

	} elseif ( $atts['r'] !== '' ) {
		if ( $atts['re'] == '1' ) {
			$re = 'boj-r-rejudge ';
		} else {
			$re = '';
		}

		if ( $atts['r'] == 'ac' ) {
			if ( $atts['s'] == '' ) {
				return '<span class="'.$re.'boj-r-ac">맞았습니다!!</span>';
			} else {
				return '<span class="'.$re.'boj-r-ac">'.$atts['s'].'점</span>';
			}
		} elseif ( $atts['r'] == 'pac' ) {
			if ( $atts['s'] == '' ) {
				return '<span class="'.$re.'boj-r-pac">맞았습니다!!</span>';
			} else {
				return '<span class="'.$re.'boj-r-pac">'.$atts['s'].'점</span>';
			}
		} elseif ( $atts['r'] == 'wa' ) {
			return '<span class="'.$re.'boj-r-wa">틀렸습니다</span>';
		} elseif ( $atts['r'] == 'pe' ) {
			return '<span class="'.$re.'boj-r-pe">출력 형식이 잘못되었습니다</span>';
		} elseif ( $atts['r'] == 'tle' ) {
			return '<span class="'.$re.'boj-r-tle">시간 초과</span>';
		} elseif ( $atts['r'] == 'mle' ) {
			return '<span class="'.$re.'boj-r-mle">메모리 초과</span>';
		} elseif ( $atts['r'] == 'ole' ) {
			return '<span class="'.$re.'boj-r-ole">출력 초과</span>';
		} elseif ( $atts['r'] == 'rte' ) {
			return '<span class="'.$re.'boj-r-rte">런타임 에러</span>';
		} elseif ( $atts['r'] == 'ce' ) {
			return '<span class="'.$re.'boj-r-ce">컴파일 에러</span>';
		} elseif ( $atts['r'] == 'wait' ) {
			return '<span class="'.$re.'boj-r-wait">기다리는 중</span>';
		} elseif ( $atts['r'] == 'rejudge-wait' ) {
			return '<span class="'.$re.'boj-r-rejudge-wait">재채점을 기다리는 중</span>';
		} elseif ( $atts['r'] == 'nojudge' ) {
			return '<span class="'.$re.'boj-r-nojudge">채점하지 않음</span>';
		} elseif ( $atts['r'] == 'compile' ) {
			return '<span class="'.$re.'boj-r-compile">채점 준비 중</span>';
		} elseif ( $atts['r'] == 'judging' ) {
			return '<span class="'.$re.'boj-r-judging">채점 중</span>';
		} elseif ( $atts['r'] == 'co' ) {
			return '<span class="'.$re.'boj-r-co">채점 불가</span>';
		} elseif ( $atts['r'] == 'del' ) {
			return '<span class="'.$re.'boj-r-del">삭제된 제출</span>';
		} 

	} elseif ($atts['t'] !== '') {
		
		$t = solved_tier($atts['t']);

		if ( $atts['s'] == '1' ) {
			return '<img src="https://static.solved.ac/tier_small/'.$t[0].'.svg" class="boj-t-img boj-t-'.$t[0].'" alt="'.$t[1].'"><span class="boj-t-text-'.$t[2].'"> '.$t[1].'</span>';
		} else {
			return '<img src="https://static.solved.ac/tier_small/'.$t[0].'.svg" class="boj-t-img boj-t-'.$t[0].'" alt="'.$t[1].'">';
		}
		
	} elseif ($atts['u'] !== '') {

		$handle = $atts['u'];
		$file = __DIR__.'/users/'.$handle.'.json';
		$solvedurl = 'https://solved.ac/api/v3/user/show?handle='.$handle;
		
		if (file_exists($file)) {
			$filetime = filemtime($file);
			$now = time();
			if ( ($now - $filetime) >= 86400 ) {
				$userinfo = file_get_contents($solvedurl);
				file_put_contents($file, $userinfo);
			}
		} else {
			if (get_http_response_code($solvedurl) == '404') {
				return 'NOT FOUND :(';
			} else {
				$userinfo = file_get_contents($solvedurl);
				file_put_contents($file, $userinfo);
			}
		}
		
		$info = json_decode(file_get_contents($file), true);
		$t = solved_tier($info['tier']);

		return '<a href="https://solved.ac/profile/'.$handle.'" target="_blank" class="boj-u"><img src="https://static.solved.ac/tier_small/'.$info['tier'].'.svg" class="boj-t-img"><span class="boj-t-text-'.$t[2].'">&nbsp;'.$handle.'</span></a>';
		
	}

}

add_shortcode( 'boj', 'bojtag' );

// CSS 적용하기
wp_register_style( 'boj-style', plugins_url('style.css', __FILE__) );
wp_enqueue_style( 'boj-style' );

?>
