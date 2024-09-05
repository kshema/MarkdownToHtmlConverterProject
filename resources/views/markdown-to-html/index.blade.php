
  <h1>Markdown to HTML Converter</h1>
  <form method="POST" action="/convert">
    @csrf
      <div>
        <textarea name = "markdownText" rows="20" cols="75"></textarea>
      </div>
      <div style="margin-top: 20px"><button type="submit">Submit</button> </div>
  </form>
