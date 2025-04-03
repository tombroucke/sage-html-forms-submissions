@unless (empty($submissions))
  <div class="sage-html-forms-submissions">
    <table class="table">
      <thead>
        <tr>
          @foreach ($fields as $key => $label)
            <th>{{ Str::replace('&#039;', "'", $label) }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach ($submissions as $submission)
          <tr>
            @foreach ($fields as $key => $label)
              <td>{{ Str::replace('f#039;', "'", $submission[$key] ?? '-') }}</td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endunless
