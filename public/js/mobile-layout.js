/**
 * Shared mobile layout helpers for sidebar drawer & dropdown positioning.
 */
(function () {
    'use strict';

    var MOBILE_BP = 991;

    function isMobile() {
        return window.innerWidth <= MOBILE_BP;
    }

    function getTrigger(selectors) {
        for (var i = 0; i < selectors.length; i++) {
            var el = document.querySelector(selectors[i]);
            if (el) return el;
        }
        return null;
    }

    function positionDropdown(dropdown, trigger) {
        if (!dropdown || !trigger || !isMobile()) return;

        var rect = trigger.getBoundingClientRect();
        var width = Math.min(dropdown.classList.contains('notif-dropdown') ? 360 : 320, window.innerWidth - 24);

        dropdown.style.position = 'fixed';
        dropdown.style.top = (rect.bottom + 8) + 'px';
        dropdown.style.right = Math.max(12, window.innerWidth - rect.right) + 'px';
        dropdown.style.left = 'auto';
        dropdown.style.width = width + 'px';
        dropdown.style.zIndex = '10001';
    }

    function resetDropdown(dropdown) {
        if (!dropdown) return;
        dropdown.style.position = '';
        dropdown.style.top = '';
        dropdown.style.right = '';
        dropdown.style.left = '';
        dropdown.style.width = '';
        dropdown.style.zIndex = '';
    }

    function repositionOpenDropdowns() {
        var profileDropdown = document.getElementById('profileDropdown');
        if (profileDropdown && profileDropdown.classList.contains('show')) {
            positionDropdown(profileDropdown, getTrigger([
                '#mobileProfileTrigger',
                '#profileDropdownWrap .user-profile-badge'
            ]));
        }

        var notifDropdown = document.getElementById('notifDropdown');
        if (notifDropdown && notifDropdown.classList.contains('show')) {
            positionDropdown(notifDropdown, getTrigger([
                '#mobileNotifTrigger',
                '#notifDropdownWrap .notif-bell'
            ]));
        }
    }

    function containsTarget(ids, selectors, target) {
        var i;

        for (i = 0; i < ids.length; i++) {
            var byId = document.getElementById(ids[i]);
            if (byId && byId.contains(target)) return true;
        }

        for (i = 0; i < selectors.length; i++) {
            var bySelector = document.querySelector(selectors[i]);
            if (bySelector && bySelector.contains(target)) return true;
        }

        return false;
    }

    window.isMobileLayout = isMobile;
    window.positionMobileDropdowns = repositionOpenDropdowns;

    window.isProfileAreaClick = function (target) {
        return containsTarget(
            ['profileDropdownWrap', 'mobileProfileTrigger', 'profileDropdown'],
            [],
            target
        );
    };

    window.isNotifAreaClick = function (target) {
        return containsTarget(
            ['notifDropdownWrap', 'mobileNotifTrigger', 'notifDropdown'],
            [],
            target
        );
    };

    window.initMobileSidebarLinks = function () {
        document.querySelectorAll('.sidebar-menu a, .sidebar-submenu a, .sidebar-bottom a:not(.logout)').forEach(function (link) {
            link.addEventListener('click', function () {
                if (isMobile() && typeof window.closeMobileSidebar === 'function') {
                    window.closeMobileSidebar();
                }
            });
        });
    };

    document.addEventListener('DOMContentLoaded', function () {
        initMobileSidebarLinks();

        document.addEventListener('click', function () {
            setTimeout(repositionOpenDropdowns, 0);
        }, true);

        window.addEventListener('resize', repositionOpenDropdowns);
        window.addEventListener('scroll', repositionOpenDropdowns, true);
    });
})();
