export default {
  filters: {
    date(value) {
      if (!value) {
        return ''
      }

      return new Date(value).toLocaleDateString()
    }
  }
}
