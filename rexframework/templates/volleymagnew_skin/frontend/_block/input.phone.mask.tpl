<script src="/skin/volleymag_skin/frontend/js/inputmask-multi/k_inputmask.js"></script>
<script src="/skin/volleymag_skin/frontend/js/inputmask-multi/jquery.bind-first.js"></script>
<script src="/skin/volleymag_skin/frontend/js/inputmask-multi/jquery.inputmask-multi.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		initPhoneMask();
		$('input[data-init-inputmask]').on('click, focus', function() {
			var $this = $(this);
			if (!$this.val().length) {
				$this.val('+38');
			}
		});
	});

	function initPhoneMask() {
		if ('function' != typeof(jQuery.fn.inputmasks)) {
			return false;
		}

		var maskList = $.masksSort($.masksLoad("/skin/volleymag_skin/frontend/js/inputmask-multi/phone-codes.json"), ['#'], /[0-9]|#/, "mask");

		var maskOpts = {
			inputmask: {
				definitions: {
					'#': {
						validator: "[0-9]",
						cardinality: 1
					}
				},
				//clearIncomplete: true,
				showMaskOnHover: false,
				autoUnmask: true
			},
			match: /[0-9]/,
			replace: '#',
			list: maskList,
			listKey: "mask"
		};

		var selectorPhone = '[data-init-inputmask]';
		$(selectorPhone).each(function(obj, index){
			$(this).inputmasks(maskOpts).trigger('change');
		});
	}
</script>