/**
 * Truncate a string to a specified maximum length and append "..." if it exceeds it.
 *
 * @param {string} text - The text to truncate.
 * @param {number} maxLength - The maximum allowed length (default: 18).
 * @returns {string} The truncated string.
 */
export const truncateName = (text, maxLength = 14) => {
  let truncateName = text.split(' ')[0] + ' '
  let otherName = text.split(' ').slice(1)
  otherName.map(word => {
    truncateName += word[0] + ". "
  })

  return truncateName.trim();
};
