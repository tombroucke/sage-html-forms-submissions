@unless (empty($submissions))
  <div class="sage-html-forms-submissions">
    <table class="table">
      <thead>
        <tr>
          @foreach ($fields as $key => $label)
            <th>{{ esc_html($label) }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach ($submissions as $submission)
          <tr>
            @foreach ($fields as $key => $label)
              <td>{{ esc_html($submission[$key] ?? '-') }}</td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endunless
