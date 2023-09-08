<div class="sage-html-forms-submissions">
  <table class="table">
    <thead>
      <tr>
        @foreach($fields as $key => $value)
          <th>{{ $value }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($submissions as $submission)
        <tr>
          @foreach($submission as $key => $value)
            <td>{{ $value }}</td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
