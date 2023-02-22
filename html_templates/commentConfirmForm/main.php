

<div class="confirmForm">
    <div>
        <h2>Вы уверены?</h2>
    </div>
    <div>
        <textarea id="commentFormText" name="name" rows="10" cols="80" placeholder="Комментарий"></textarea>
    </div>
    <div class="buttonBlock">
        <button onclick="<?=$funcName?>; closeCommentForm('commentConfirmForm');" style="min-width: 140px; height: 40px; margin-top: 0px;">
            Да
        </button>
        <button onclick="fUnMsgBlock('commentConfirmForm')" style="min-width: 140px; height: 40px; margin-top: 0px;">
            Нет
        </button>
    </div>
</div>
