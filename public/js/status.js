document.addEventListener("DOMContentLoaded", () => {
    // 给所有的下拉框绑定change事件，判断是否有任何值发生变化
    document.querySelectorAll("select.status-dropdown").forEach(select => {
        // 存储初始值（已有data-original属性也可作为参考）
        select.dataset.original = select.value;
        select.addEventListener("change", function(e){
            let finishButton = document.querySelector("button.finish-status-btn");
            // 检查所有下拉框是否有变化
            let changed = false;
            document.querySelectorAll("select.status-dropdown").forEach(s => {
                if (s.value !== s.dataset.original) {
                    changed = true;
                }
            });
            finishButton.style.display = changed ? "inline-block" : "none";
        });
    });

    // 监听全局 Finish 按钮点击事件，汇总所有下拉框的变更数据并提交表单
    document.querySelector("button.finish-status-btn").addEventListener("click", function(e){
        // 新建一个隐藏表单，提交所有变更（使用数组结构）
        const form = document.createElement("form");
        form.method = "post";
        form.action = "/CISC3003-ProjectAssignment/controllers/Enrollments_managers.php?action=updateStatus";

        // 遍历所有下拉框，找出值已改变的项
        document.querySelectorAll("select.status-dropdown").forEach(select => {
            if (select.value !== select.dataset.original) {
                const td = select.closest("td.status-td");
                const enrollmentID = td.getAttribute("data-id");

                const inputEnrollment = document.createElement("input");
                inputEnrollment.type = "hidden";
                inputEnrollment.name = "updates[" + enrollmentID + "][enrollmentID]";
                inputEnrollment.value = enrollmentID;
                form.appendChild(inputEnrollment);

                const inputCurrent = document.createElement("input");
                inputCurrent.type = "hidden";
                inputCurrent.name = "updates[" + enrollmentID + "][currentStatus]";
                inputCurrent.value = select.dataset.original;
                form.appendChild(inputCurrent);

                const inputNew = document.createElement("input");
                inputNew.type = "hidden";
                inputNew.name = "updates[" + enrollmentID + "][newStatus]";
                inputNew.value = select.value;
                form.appendChild(inputNew);
            }
        });
        document.body.appendChild(form);
        form.submit();
    });
});