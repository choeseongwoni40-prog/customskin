/**
 * Custom JavaScript
 */
(function($) {
    'use strict';

    // 전면 광고 관리
    let lastInterstitialTime = localStorage.getItem('lastInterstitialTime') || 0;
    let pageLoadCount = parseInt(localStorage.getItem('pageLoadCount') || '0');
    
    function showInterstitialAd() {
        const now = Date.now();
        const oneMinute = 60 * 1000;
        
        // 1분 경과 확인
        if (now - lastInterstitialTime < oneMinute) {
            return;
        }
        
        // 페이지 전환마다 표시
        pageLoadCount++;
        
        if (pageLoadCount >= 2) { // 2페이지마다
            const overlay = document.getElementById('interstitial-ad-overlay');
            if (overlay) {
                overlay.innerHTML = getInterstitialAdHTML();
                overlay.style.display = 'flex';
                lastInterstitialTime = now;
                pageLoadCount = 0;
                
                localStorage.setItem('lastInterstitialTime', now);
                localStorage.setItem('pageLoadCount', '0');
            }
        } else {
            localStorage.setItem('pageLoadCount', pageLoadCount);
        }
    }
    
    function getInterstitialAdHTML() {
        return `
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 99999; display: flex; align-items: center; justify-content: center;">
            <div style="position: relative; background: white; border-radius: 20px; padding: 20px; max-width: 90%; max-height: 90%; overflow: auto;">
                <button onclick="closeInterstitialAd()" style="position: absolute; top: 10px; right: 10px; background: #ff4444; color: white; border: none; border-radius: 50%; width: 36px; height: 36px; font-size: 20px; cursor: pointer; z-index: 1;">✕</button>
                <div id="interstitial-ad-content" style="margin-top: 40px;"></div>
            </div>
        </div>
        `;
    }
    
    window.closeInterstitialAd = function() {
        const overlay = document.getElementById('interstitial-ad-overlay');
        if (overlay) {
            overlay.style.display = 'none';
            overlay.innerHTML = '';
        }
    };
    
    // 페이지 로드 시 전면 광고 체크
    $(window).on('load', function() {
        setTimeout(showInterstitialAd, 500);
    });
    
    // 탭 활성화 관리
    $('.tab-link').on('click', function(e) {
        const href = $(this).attr('href');
        
        // 내부 링크인 경우에만 활성화 처리
        if (href.startsWith('#')) {
            e.preventDefault();
            $('.tab-link').removeClass('active');
            $(this).addClass('active');
        }
    });
    
    // 카드 클릭 추적 (고클릭률을 위한 시각적 피드백)
    $('.support-card, .card-btn').on('click', function() {
        $(this).css('transform', 'scale(0.98)');
        setTimeout(() => {
            $(this).css('transform', '');
        }, 150);
    });
    
})(jQuery);
