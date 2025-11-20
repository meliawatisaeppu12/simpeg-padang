$("#btn-add-hukdis").on("click", function () {
    alertify
        .dialog("confirm")
        .set({
            transition: "zoom",
            message: "<b>Alert</b>",
            closableByDimmer: false,
            resizable: true,
            closable: false,
            movable: false,
            reverseButtons: true,
            title: 'Tambah Riwayat Hukdis',
            labels: {
                ok: "Save Changes",
                cancel: "Close",
            },
            oncancel: function () {
                alertify.set("notifier", "position", "top-right");
                alertify.warning("Dibatalkan");
            },
            onok: function () {
                alertify.set("notifier", "position", "top-right");
                alertify.success("Success");
            },
        })
        .resizeTo("50%", "75%")
        .show();
});
