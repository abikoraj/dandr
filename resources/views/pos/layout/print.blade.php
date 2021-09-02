<span class="text-white "
style="background: rgba(255, 255, 255, 0.2);
    padding: 5px 10px;
    margin-left: 5px;
    color: white;
    font-weight: 600;
    font-size: 14px;"
>
    <input type="radio" name="print-type" id="print-type-0" value="0" class="print-type mr-1" onchange="printSetting.setType()"> Local
    <span class="mx-2">|</span>
    <input type="radio" name="print-type" id="print-type-1" value="1" class="print-type ml-1" onchange="printSetting.setType()" checked> 
    <span class="badge " id="print-server-status" onclick="printSetting.reconnectServer();">
        Printer
    </span>
</span>