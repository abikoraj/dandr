<div id="particulars" class=" py-2">
    <div id="particular-container">
      <table>
        <tr class="table-header">
          <th>Particular</th>
          <th>Rate</th>
          <th>Qty</th>
          <th>Total</th>
          <th>Discount</th>
          @if (env('companyUseTax',false))
            <th>Taxable</th>
            <th>Tax</th>
          @endif
          <th>Grand Total</th>
        </tr>
        <tbody id="bill-items">
        </tbody>
      </table>
    </div>
  </div>
