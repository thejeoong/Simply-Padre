

<form class="pinc-custom-search" action="/search_results" method="get">
    <div class="pinc-search-wrapper">
        
        <input type="text" name="q" value="<?php echo $_REQUEST['q'];?>" placeholder="What are you looking for?" class="pinc-main-input">
        
        <!-- CLEAR BUTTON -->
        <button type="button" class="pinc-clear-icon" style="display:none;">
            <i class="bi bi-x-circle"></i>
        </button>

        <button type="submit" class="pinc-submit-icon">
            <i class="bi bi-search"></i>
        </button>

    </div>
</form>
/* ── Inner Wrapper ── */
.pinc-search-wrapper {
    position: relative !important;
    width: 90% !important;
    padding: 0 !important;
    margin: 10px auto 10px auto !important;
    box-sizing: border-box !important;
}

/* ── Unique Input Field ── */
.pinc-main-input {
    height: 40px !important;
    width: 100% !important;
    border-radius: 50px !important;
    padding: 0 75px 0 15px !important; /* Space for icon on right, padding on left */
    border: 1px solid rgb(62, 126, 163) !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
    font-size: 13px !important;
    background: #ffffff !important;
    box-sizing: border-box !important; /* Prevents overflow */
    outline: none !important;
}

/* ── Unique Icon Button ── */
.pinc-submit-icon {
    position: absolute !important;
    right: 15px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    background: transparent !important;
    border: none !important;
    color: rgb(62, 126, 163) !important;
    font-size: 16px !important;
	font-weight: 700;
    cursor: pointer !important;
    padding: 0 !important;
    z-index: 10 !important;
    outline: none !important;
}

.pinc-submit-icon:hover {
    color: #333 !important;
}

/* ── Clear (X) Icon ── */
.pinc-clear-icon {
    position: absolute !important;
    right: 35px !important; /* LEFT of search icon */
    top: 50% !important;
    transform: translateY(-50%) !important;
    background: transparent !important;
    border: none !important;
    color: #999 !important;
    font-size: 14px !important;
    cursor: pointer !important;
    z-index: 10 !important;
    outline: none !important;
}

.pinc-clear-icon:hover {
    color: #333 !important;
}
/* ── Mobile Width Fix ── */
@media (max-width: 767px) {
    .pinc-custom-search {
        max-width: 95% !important;
        margin: 10px auto 10px auto !important;
    }
    .pinc-main-input {
        height: 38px !important;
    }
}
<script>
(function() {
    function applySearchBtn() {
        var btn = document.querySelector('.sp-search-btn');
        if (!btn) return;
        if (window.innerWidth >= 768) {
            btn.style.setProperty('border-top-left-radius', '0', 'important');
            btn.style.setProperty('border-bottom-left-radius', '0', 'important');
            btn.style.setProperty('-webkit-border-top-left-radius', '0', 'important');
            btn.style.setProperty('-webkit-border-bottom-left-radius', '0', 'important');
            btn.style.setProperty('border-top-right-radius', '50px', 'important');
            btn.style.setProperty('border-bottom-right-radius', '50px', 'important');
            btn.style.setProperty('border-left', 'none', 'important');
            btn.style.setProperty('height', '50px', 'important');
} else {
            btn.style.setProperty('border-top-left-radius', '50px', 'important');
            btn.style.setProperty('border-bottom-left-radius', '50px', 'important');
            btn.style.setProperty('border-top-right-radius', '50px', 'important');
            btn.style.setProperty('border-bottom-right-radius', '50px', 'important');
            btn.style.setProperty('-webkit-border-top-left-radius', '50px', 'important');
            btn.style.setProperty('-webkit-border-bottom-left-radius', '50px', 'important');
            btn.style.setProperty('border-left', '1px solid rgb(62, 126, 163)', 'important');
            btn.style.removeProperty('height');
        }
    }
    applySearchBtn();
    window.addEventListener('resize', applySearchBtn);
})();
	
document.addEventListener("DOMContentLoaded", function () {
    const input = document.querySelector(".pinc-main-input");
    const clearBtn = document.querySelector(".pinc-clear-icon");

    function toggleClear() {
        if (input.value.length > 0) {
            clearBtn.style.display = "block";
        } else {
            clearBtn.style.display = "none";
        }
    }

    // Show/hide on typing
    input.addEventListener("input", toggleClear);

    // Clear input when clicked
    clearBtn.addEventListener("click", function () {
        input.value = "";
        input.focus();
        toggleClear();
    });

    // Run on load (for pre-filled value)
    toggleClear();
});
</script>
/* ── Inner Wrapper ── */
.pinc-search-wrapper {
    position: relative !important;
    width: 90% !important;
    padding: 0 !important;
    margin: 10px auto 10px auto !important;
    box-sizing: border-box !important;
}

/* ── Unique Input Field ── */
.pinc-main-input {
    height: 40px !important;
    width: 100% !important;
    border-radius: 50px !important;
    padding: 0 75px 0 15px !important; /* Space for icon on right, padding on left */
    border: 1px solid rgb(62, 126, 163) !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
    font-size: 13px !important;
    background: #ffffff !important;
    box-sizing: border-box !important; /* Prevents overflow */
    outline: none !important;
}

/* ── Unique Icon Button ── */
.pinc-submit-icon {
    position: absolute !important;
    right: 15px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    background: transparent !important;
    border: none !important;
    color: rgb(62, 126, 163) !important;
    font-size: 16px !important;
	font-weight: 700;
    cursor: pointer !important;
    padding: 0 !important;
    z-index: 10 !important;
    outline: none !important;
}

.pinc-submit-icon:hover {
    color: #333 !important;
}

/* ── Clear (X) Icon ── */
.pinc-clear-icon {
    position: absolute !important;
    right: 35px !important; /* LEFT of search icon */
    top: 50% !important;
    transform: translateY(-50%) !important;
    background: transparent !important;
    border: none !important;
    color: #999 !important;
    font-size: 14px !important;
    cursor: pointer !important;
    z-index: 10 !important;
    outline: none !important;
}

.pinc-clear-icon:hover {
    color: #333 !important;
}
/* ── Mobile Width Fix ── */
@media (max-width: 767px) {
    .pinc-custom-search {
        max-width: 95% !important;
        margin: 10px auto 10px auto !important;
    }
    .pinc-main-input {
        height: 38px !important;
    }
}
<script>
(function() {
    function applySearchBtn() {
        var btn = document.querySelector('.sp-search-btn');
        if (!btn) return;
        if (window.innerWidth >= 768) {
            btn.style.setProperty('border-top-left-radius', '0', 'important');
            btn.style.setProperty('border-bottom-left-radius', '0', 'important');
            btn.style.setProperty('-webkit-border-top-left-radius', '0', 'important');
            btn.style.setProperty('-webkit-border-bottom-left-radius', '0', 'important');
            btn.style.setProperty('border-top-right-radius', '50px', 'important');
            btn.style.setProperty('border-bottom-right-radius', '50px', 'important');
            btn.style.setProperty('border-left', 'none', 'important');
            btn.style.setProperty('height', '50px', 'important');
} else {
            btn.style.setProperty('border-top-left-radius', '50px', 'important');
            btn.style.setProperty('border-bottom-left-radius', '50px', 'important');
            btn.style.setProperty('border-top-right-radius', '50px', 'important');
            btn.style.setProperty('border-bottom-right-radius', '50px', 'important');
            btn.style.setProperty('-webkit-border-top-left-radius', '50px', 'important');
            btn.style.setProperty('-webkit-border-bottom-left-radius', '50px', 'important');
            btn.style.setProperty('border-left', '1px solid rgb(62, 126, 163)', 'important');
            btn.style.removeProperty('height');
        }
    }
    applySearchBtn();
    window.addEventListener('resize', applySearchBtn);
})();
	
document.addEventListener("DOMContentLoaded", function () {
    const input = document.querySelector(".pinc-main-input");
    const clearBtn = document.querySelector(".pinc-clear-icon");

    function toggleClear() {
        if (input.value.length > 0) {
            clearBtn.style.display = "block";
        } else {
            clearBtn.style.display = "none";
        }
    }

    // Show/hide on typing
    input.addEventListener("input", toggleClear);

    // Clear input when clicked
    clearBtn.addEventListener("click", function () {
        input.value = "";
        input.focus();
        toggleClear();
    });

    // Run on load (for pre-filled value)
    toggleClear();
});
</script>