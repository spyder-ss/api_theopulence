<script>
    $(document).ready(function () {
        $(".Alphabet").on('keypress', function (evt) {
            regex = /^[A-Za-z\s]*$/;
            var regex = new RegExp(regex);
            var str = String.fromCharCode(!evt.charCode ? evt.which : evt.charCode);
            if (regex.test(str)) {
                return true;
            } else {
                evt.preventDefault();
                return false;
            }
        });
    });

    $(document).ready(function () {
        $(".Numeric").on('keypress', function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        });
    });

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function isCharacter(evt) {
        regex = /^[A-Za-z\s]*$/;
        var regex = new RegExp(regex);
        var str = String.fromCharCode(!evt.charCode ? evt.which : evt.charCode);
        if (regex.test(str)) {
            return true;
        } else {
            evt.preventDefault();
            return false;
        }
    }

</script>
